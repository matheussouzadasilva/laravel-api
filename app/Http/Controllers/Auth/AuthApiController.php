<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth; // USE usando facade para simplificar e nao digitar todo o caminho
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;
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
        
        //$data = Session()->all();
        //Session::forget('key');
        //Session::pull('key');
        //Session::getHandler()->destroy(Session::getId());
        //Session::flush();
        //dd(Session());
        
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
    
    //so falta enviar email com o link de redefinição
    public function esqueciSenha(Request $request)
    {
       $data = $request->json()->all();

        $validate = validator($data, [
            'email' => 'required|min:7'
        ]);

        if ($validate->fails()) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }
        
        $id = DB::table('users')->where('email', $data["email"])->first()->id;
        $user = User::find($id);
        
        $rand1 = substr(md5(mt_rand()), -32);
        $rand2 = substr(hash('sha256',mt_rand()), 0, 32);
        $unique = $rand2.$rand1;
        
        $user->forgot_token = $unique; 

        if (!$user->save()) {
            return response()->json(['validate_error' => 'error_update_forgot_password'], 500);
        }
        
        $arrParams["url"] = "http://192.168.33.10/client-laravel-api/adm/formularios/resetar.senha.htm?forgottk=".$user->forgot_token;
        Mail::to($data["email"])->send(new OrderShipped($arrParams));
        return response()->json(null, 200);
    }
    
    public function alterarSenha(Request $request)
    {
        $data = $request->json()->all();

        $validate = validator($data, [
            'forgot_token' => 'required|min:64|max:64',//token da senha esquecida
            'password' => 'required|min:6|confirmed',//nova senha
            'password_confirmation' => 'required|min:6'//confirmacao nova senha
            ]);

        if ($validate->fails()) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }
        
        $id = 0;
        
        if (array_key_exists("forgot_token", $data)) {
            $id = DB::table('users')->where('forgot_token', $data["forgot_token"])->first()->id;
        }
        
        if ($id > 0) {
            $user = User::find($id);
            $user->password = bcrypt($data["password"]); 
            $user->forgot_token = NULL;

            if (!$user->save()) {
                return response()->json(['validate_error' => 'error_update_password'], 500);
            }
            
            return response()->json(null, 200);
        }
        
        return response()->json(['validate_error' => 'error_update_password'], 500);
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
    protected function validaAlteracaoSenha()
    {
        return  [
            'password_current' => 'required|min:6',//senha atual
            'password' => 'required|min:6|dumbpwd|confirmed',//nova senha
            'password_confirmation' => 'required|min:6|dumbpwd|same:password'//confirmação nova senha
        ];
    }

    /**
     * altera a senha do usuario que esta logado
     *
     */
    public function alterarSenhaLogado(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(null, 401);
        }

        $data = $request->json()->all();

        $validate = validator($data, $this->validaAlteracaoSenha());

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
