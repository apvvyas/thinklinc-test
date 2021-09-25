<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use App\Models\User;

class LoginController extends Controller
{

    protected $user = '';

    function __construct(User $user){
        $this->user = $user;
    }
    /**
     * Login a user
     * 
     * @param LoginRequest $request
     * 
     * @return Illuminate\Http\Response
     */
    function attempt(LoginRequest $request){
        
        $token = $this->user->verify($request->email, $request->password);
        
        if($token){

            return response()->json([
                'token' => $token,
                'message' => __('auth.successful')
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => __('auth.failed')
        ], Response::HTTP_FORBIDDEN);
    }
}
