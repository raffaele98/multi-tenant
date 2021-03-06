<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\UserRequest;

use App\Customer;
use App\User;
use App\Role;

use App\Http\Controllers\Controller;
use Auth;
use Dingo\Api\Contract\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return Response()->json([
            'users' => User::where('subscribed','=',true)->with('roles', 'roles.permissions', 'customers')->get()
        ]);
    }

    public function show($id)
    {
        return Response()->json([
            'users' => User::with('roles', 'customers')->find($id)
        ]);
    }

    public function store(UserRequest $request) {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'cell_phone' => $request->input('cell_phone'),
            'fax' => $request->input('fax'),
            'address' => $request->input('address'),
            'postcode' => $request->input('postcode'),
            'province' => $request->input('province'),
            'city' => $request->input('city'),
            'nation' => $request->input('nation'),
            'ibernate' => $request->input('ibernate'),
            'notify' => $request->input('notify'),
            'subscribed' => true
        ]);

        $user->customers()->attach($request->input('customer_id'));
        $user->roles()->attach($request->input('role_id'));

        return Response()->json([
            'status' => 'user created successfully'
        ]);
    }

    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->first_name = $request->input('first_name', null);
        $user->last_name = $request->input('last_name', null);
        $user->cell_phone = $request->input('cell_phone', null);
        $user->fax = $request->input('fax', null);
        $user->address = $request->input('address', null);
        $user->postcode = $request->input('postcode', null);
        $user->province = $request->input('province', null);
        $user->city = $request->input('city', null);
        $user->nation = $request->input('nation', null);
        $user->save();

        if($request->has('customer_id'))
            $user->customers()->sync([$request->input('customer_id')]);
        else
            $user->customers()->detach();

        if(!empty($request->input('role_id')))
            $user->roles()->sync([$request->input('role_id')]);

        return Response()->json([
            'status' => 'user updated successfully'
        ]);
    }

    public function destroy($id) {
        $user = User::find($id);
        $user->delete();

        return Response()->json([
            'status' => 'user deleted successfully'
        ]);
    }

    public function getSubscriber()
    {
        return Response()->json([
            'users' => User::where('subscribed','=',false)->get()
        ]);
    }

    public function confirmSubscribe(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $role = Role::find($request->input('role_id'));

        $user->subscribed = true;
        $user->save();

        $user->roles()->attach($role->id);

        return Response()->json([
            'status' => 'The Administrator has allowed your subscription'
        ]);
    }

    public function RemoveSubscribe($id)
    {
        $user = User::find($id);
        $user->delete();

        return Response()->json([
            'status' => 'The Administrator has removed you to the agency'
        ]);
    }

    public function Ibernate($id = null)
    {
        if($id)
        {
            $user = User::find($id);
            $user->ibernate = true;
            $user->save();
        }
    }

    public function getAuthPusher(\Dingo\Api\Http\Request $request)
    {
        $this->validate($request, [
            'channel_name' => 'required',
            'socket_id' => 'required',
        ]);

        $options = array(
            'cluster' =>  env('PUSHER_CLUSTER'),
            'encrypted' => true
        );
        $pusher = new \Pusher(
            env('PUSHER_KEY'),
            env('PUSHER_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $auth = $pusher->socket_auth($request->channel_name, $request->socket_id);

        return response()->json($auth);
    }


}
