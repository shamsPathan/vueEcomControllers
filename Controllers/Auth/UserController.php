<?php
/**
 * Author: Shams
 */


namespace App\Http\Controllers\Auth;

use App\Classes\User;
use App\UserModel;
use Illuminate\Http\Request;

class UserController {

    public function __construct()
    {
        $this->user  = new User(new UserModel());
    }

    public function login(Request $request)
    {            
        return json_encode(['status'=>100]);

        // $this->user->info($request->all());  
        // return $this->user->login();

    }

    public function register(Request $request)
    {
        
        return $this->user->create($request->all())?
            redirect('/')->with('success','Please check your email or phone for comfirmation of registration'):
            redirect('/')->with('error','Registration Failed');;

    }

}
