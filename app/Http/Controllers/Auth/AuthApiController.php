<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth; // USE usando facade para simplificar e nao digitar todo o caminho
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logado()
    {
        if (!Auth::check()) {
            return response()->json(null, 401);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();

        if (!Auth::check()) {
            return response()->json(null, 401);
        }
    }
    
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    public function refreshToken(Request $request)
    {
        if( !$token = $request->get('token') ) {            
            return response()->json(['error' => 'token_not_send'], 401);
        }

        try {
            $token = JWTAuth::refresh($token);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'token_invalid'], 401);
        }

        return response()->json(compact('token'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validasenha()
    {
        return  [
            'password_current' => 'required|min:6',//senha atual
            'password' => 'required|min:6|confirmed',//nova senha
            'password_confirmation' => 'required|min:6'//confirmacao nova senha
        ];
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validaSenhaLogado()
    {
        return  [
            'password_current' => 'required|min:6',//senha atual
            'password' => 'required|min:6|confirmed',//nova senha
            'password_confirmation' => 'required|min:6|same:password'//confirmacao nova senha
        ];
    }

    /**
     * altera a senha do usuario que esta logado
     *
     */
    public function alterarsenha(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(null, 401);
        }

        $data = $request->json()->all();

        $validate = validator($data, $this->validaSenhaLogado());

        if ($validate->fails()) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }

        $check = auth()->validate([
            'password' => $data["password_current"] 
        ]);

        if (!$check) {
            return response()->json(['validate_error' => 'password_current_invalid'], 422);
        }

        $id = Auth::id();
        $user = User::find($id);
        $user->password = bcrypt($data["password"]); 

        if (!$user->save()) {
            return response()->json(['validate_error' => 'error_update_password'], 500);
        }

        return response()->json(null, 200);
    }
}
