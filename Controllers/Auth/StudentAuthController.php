<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\InstructorUser;
use App\Models\Frontend\StudentUser;
use App\Profile;
use Illuminate\Http\Request;
use  App\Classes\Student;

class StudentAuthController extends Controller
{

    public function __construct()
    {
        $this->student = new Student(new StudentUser);
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'body'  => 'required'
        ]);

        switch ($this->student->login($request->body['email'], $request->body['password'])) {
            case 0:
                $returnValue = json_encode(['status' => 200, 'auth' => false, 'message' => 'Please register first!']);
                break;
            case 1:
                $returnValue = json_encode(['status' => 200, 'auth' => true, 'message' => 'You are logged In!']);
                break;
            case -1:
                $returnValue = json_encode(['status' => 200, 'auth' => false, 'message' => 'You are not authorised!']);
                break;
            default:
                $returnValue = json_encode(['status' => 200, 'auth' => false, 'message' => 'I don\'t know  what happende']);
        }
        return $returnValue;
    }

    public function logout()
    {
        switch ($this->student->logout()) {
            case true:
            case false:
            default:
                return json_encode(['status' => 200, 'auth' => false, 'message' => 'You are Logged Out. ThankYou!']);
        }
    }

    public function register(Request $request)
    {

        $user = $this->student->register($request);

        // something to do with that
        // After creating user, on success, swicth is converting it to int which is getting error(returned model)
        // Rearrange it
        switch ($user) {
            case 0:
                $returnValue = $user;
                break;
            case -1:
                $returnValue = json_encode(['error' => 'This email is registered.Please login']);
                break;
            case 404:
                $returnValue = json_encode(['error' => 'Something went wrong with registration']);
                break;
            default:
                $returnValue = $user;
        }
        return $returnValue;
    }
}
