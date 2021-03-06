<?php

namespace App\Api\V1\Controllers;

use App\Agency;
use App\Jobs\NewSubscriber;
use App\Notifications\LoginSuccess;
use App\Notifications\NewSubscriberNotification;
use App\User;
use Auth;
use Notification;
use HipsterJazzbo\Landlord\Facades\Landlord;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class LoginController extends Controller
{
    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return compact('user');
    }

    public function login(LoginRequest $request, JWTAuth $JWTAuth)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = $JWTAuth->attempt($credentials)) {
                return response()->json(['error' => ['errors' => ['credentials' => ['Invalid Credentials']]]], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => ['errors' => ['credentials' => ['Could Not Create Token']]]], 500);
        }

        //TODO: login test notification
        broadcast(new \App\Events\Users\LoginSuccess(Auth::user()))->toOthers();
//        Auth::user()->notify(new LoginSuccess(Auth::user()));

        return response()->json([
            'agency' => Agency::where('id', '=', Auth::user()->agency_id)->withCount([
                            'customers', 'projects', 'tasks', 'users'
                        ])->first(),
            'auth' => User::with('roles', 'customers')->find(Auth::user()->id),
            'token' => $token
        ]);
    }
}
