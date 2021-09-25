<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Register a user
     * 
     * @param RegisterRequest $request
     * 
     * @return Illuminate\Http\Response
     */
    function attempt(RegisterRequest $request){
        
        $user = User::firstOrCreate([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'email_verified_at' => Carbon::now()
        ]);

        if($user){
            return response()->json([
                'token' => $user->createToken('authToken')->plainTextToken,
                'message' => __('auth.register.successful')
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => __('auth.register.failed')
        ], Response::HTTP_INTERNAL_SERVER_ERROR );
        
    }
}
