<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function valida($id = '')
    {
        return  [
            'name' => "required|min:3|max:255|unique:users,name,{$id},id",
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|dumbpwd|confirmed',
            'password_confirmation' => 'required|min:6|dumbpwd'
        ];
    }

    public function criar(Request $request)
    {
        $data = $request->json()->all();
        
        $validate = validator($data, $this->valida());

        if ( $validate->fails() ) {
            $messages = $validate->messages();

            return response()->json(['validate_error' => $messages], 422);
        }

        $data = [
            'name'      => $data["name"],
            'email'     => $data["email"],
            'password'  => bcrypt($data["password"]),
        ];

        if (!$insert = \App\User::create($data)) {
            return response()->json(['error' => 'error_insert'], 500);
        }
            
        return response()->json(['usuario' => $insert], 201);
    }
}
