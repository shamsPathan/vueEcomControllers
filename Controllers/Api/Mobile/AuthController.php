<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Frontend\StudentUser;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'password' => 'required'
        ]);

        $data = $request->all();
        
        if($data['phone']??null){
            $data['phone'] = $this->checkPhone($data['phone']);
        }
        
        $message = '';

        $user = $this->getUser($data);

        $valid = $user ? password_verify($data['password'], $user->password) : null;

        if ($valid) {

            $message = "Please use this token and your Email or Phone for each request";
            
            $data['_token'] = md5(uniqid(rand(), true));
            $user->api_token = $data['_token'];
            $user->save();

            return json_encode(['status' => 'OK', '_token' => $user->api_token, 'message' => $message]);
        } else {
            $message = "Invalid user";
        }

        return json_encode(['status' => 'false', '_token' => null, 'message' => $message]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required',
            'password' => 'required'
        ]);

        $data = $request->all();
        
        if($data['phone']??null){
            $data['phone'] = $this->checkPhone($data['phone']);
        }

        $user = $this->getUser($data);

        if (!$user) {

            $created = StudentUser::create($data);

            return $created ?
                json_encode(['status' => 'OK', 'message' => 'You are registered']) :
                json_encode(["status" => 'false', 'message' => 'Something went wrong when registering']);
        } else {
            return json_encode(["status" => 'OK', 'message' => 'Your are already registered']);
        }
    }


    private function getUser($data)
    {
        if (isset($data['email'])) {
            return StudentUser::where('email', $data['email'])->first();
        } else if (isset($data['phone'])) {
            return StudentUser::where('phone', $data['phone'])->first();
        } else {
            return null;
        }
    }

    private function checkPhone($number = null)
    {
        $phone = null;
        
        if(!$number){
            return null;
        }
    
        if (strlen($number) == 11) {
            $phone = '88'.$number;
        } else if(strlen($number) == 10) {
            $phone = '880'.$number;
        } else {
            $phone = $number;
        }

        return $phone;
    }
}
