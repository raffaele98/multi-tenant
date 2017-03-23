<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\customerRequest;
use App\Customer;
use App\User;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class customerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response()->json([
            'customers' => Customer::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(customerRequest $request)
    {
        $customer = Customer::create([
            'name' => $request->input('name'),
            'description' => $request->input('description')
        ]);

//        Auth::user()->customers()->attach($customer->id);

        return Response()->json([
            'status' => 'customer created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Response()->json([
            'customer' => Customer::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(customerRequest $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->name = $request->input('name');
        $customer->description = $request->input('description');
        $customer->save();

        return Response()->json([
            'status' => 'customer updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return Response()->json([
            'status' => 'customer deleted successfully'
        ]);
    }

    public function syncUser()
    {

    }
}