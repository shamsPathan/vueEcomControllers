<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminUser;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        // $password  = password_hash('12345678', PASSWORD_DEFAULT);
        $request->validate([
            'body'  => 'required'
        ]);

        $user = AdminUser::where('email',$request->body['email'])->first();

        if(!$user){
            return json_encode(['status'=>200,'auth'=>false,'message'=>'Please register first!']);
        }

        $verify  = password_verify($request->body['password'], $user->password);

        if($verify){
            session(['user' => $user]);
            return json_encode(['status'=>200,'auth'=>true,'message'=>'You are logged In!']);
        } else {
            return json_encode(['status'=>200,'auth'=>false,'message'=>'You are not authorised!']);
        }

        // if($user->login_attempts <=5){
        //     // login check
        //     $user->login_attempts = (int)$user->login_attempts + 1;
        //     $user->save();
        // } else {
        //     $user->blocked_till = now();
        //     // dd($interval = $user->blocked_till->diff(now()));
        //     return json_encode(['status'=>200,'auth'=>false,'message'=>'Too many attempts! Calm Down, Take a break!']);
        // }

        // dd($user);


        return json_encode(['status'=>200,'auth'=>true,'message'=>'You are logged In!']);
        return json_encode(['status'=>200,'auth'=>false,'message'=>'Doesn\'t match']);
    }

    public function logout(){
        if (session('user')){
            session()->forget('user');
            return json_encode(['status'=>200,'auth'=>false,'message'=>'You have successfully logout']);
        }
        else {
            return json_encode(['status'=>200,'auth'=>false,'message'=>'You have successfully logout']);
        }
    }
}
