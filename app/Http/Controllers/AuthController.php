<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use  App\Models\User;

use Tymon\JWTAuth\Contracts\JWTSubject;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required'

        ]);

        $name = $request->input('name');
        $email = $request->input('email');
        $password = Crypt::encrypt($request->input('password'));

        User::create(['name' => $name, 'email' => $email, 'password' => $password]);

        return response()->json(['status' => 'Success', 'operation' => 'created']);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $datos_usuario = User::where('email', $request->input('email'))->first();
        //return response()->json(Crypt::encrypt($request->input('password')));
        if($datos_usuario && Crypt::decrypt($datos_usuario->password) == $request->input('password')){
            $token = auth()->login($datos_usuario);
            if (! $token ) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            else return $this->respondWithToken($token);
        }
           
        /*$credentials = $request->only(['email', 'password']);
        $token = auth()->attempt($credentials);
       
        if (! $token = auth()->attempt($credentials)) {
            echo "Token=".$token;
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return $this->respondWithToken($token);*/
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}