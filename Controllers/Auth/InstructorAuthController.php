<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\InstructorUser;
use App\Notifications\InstructorRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class InstructorAuthController extends Controller
{
    public function login(Request $request)
    {
        // $password  = password_hash('12345678', PASSWORD_DEFAULT);
        // dd(InstructorUser::all());
        $request->validate([
            'body'  => 'required'
        ]);

        $user = InstructorUser::where('email',$request->body['email'])->first();
        
        if(!$user){
            return json_encode(['status'=>200,'auth'=>false,'message'=>'Please register first!']);
        }

        $verify  = password_verify($request->body['password'], $user->password);
        
        if($verify){
            session(['instructor' => $user]);
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
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:instructor_users,email',
            'phone' => 'required|min:11|max:15|unique:instructor_users,phone',
            'password' => 'required|min:8',
        ]);

        if($validator->fails()){
            return json_encode(['errors'=>$validator->errors()]);
        } 
        try {
            $data['first_name'] = $request->input('first_name');
            $data['last_name'] = $request->input('last_name');
            $data['email'] = $request->input('email');
            $data['phone'] = $request->input('phone');
            $data['password'] = bcrypt($request->input('password'));
            $data['varify_token'] = uniqid(time(), true).Str::random(16);

            $instructor = InstructorUser::create($data);
            // $instructor->notify(new InstructorRegister($instructor));
            dd($instructor->notify(new InstructorRegister($instructor)));

            return $instructor;
        }
        catch (\Exception $e) {

			dd($e);
		}
    }
    public function activate($token)
    {

    }
    public function logout()
    {
        if(session('instructor')){
            session()->forget('instructor');
            return json_encode(['status' => 200, 'auth' => false, 'message' => 'You are logged out successfully']);

        } else {
            return json_encode(['status' => 200, 'auth' => false, 'message' => 'You are logged out successfully']);
        }
    }
}
