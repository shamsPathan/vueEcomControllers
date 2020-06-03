<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\Student;

use App\Models\Frontend\StudentUser as StudentModel;
use App\Models\Frontend\StudentUser;
use App\Profile;

class Test extends Controller
{


    public function completed( Profile $profile )
    {
        $fields = ['first_name','last_name','public_email','phone','address','post_code','city',
                    'district','dob','blood_group','gender','image'];
        $completed = 0;
        foreach($fields as $field){
            if($profile->{$field}){
                $completed++;
            }
        }
        return (int)((100.00/count($fields))*$completed);
    }

    public function test()
    {
        $profile = Profile::where('user_id', session('student')->id)->first();

        return $profile->completed();
    }

    public function all()
    {
        $student = new Student(new StudentModel);
        dd($student->all());
    }

    public function get($id)
    {
        $student = new Student(new StudentModel);
        dd($student->get($id));
    }

    public function active($id)
    {
        $student = new Student(new StudentModel);
        dd($student->isActive($id));
    }

    public function login(string $credential, string $password)
    {
        $student = new Student(new StudentModel);
        // get 
        dd(request());
        
        dd($student->login($credential, $password));

    }

    public function info(Request $request)
    {
        $info = new \stdClass;
        $info->user_agent = $request->data['userAgent'];
        $info->timezone_offset = $request->data['timezoneOffset'];
        $info->vendor = $request->data['vendor'];
        $info->app_version = $request->data['appVersion'];
        $info->product_sub = $request->data['productSub'];
        $info->hardware_concurrency = $request->data['hardwareConcurrency'];
        $info->platform = $request->data['platform'];
        
        $student = new Student(new StudentModel);
        return $student->validMachine(1, (array)$info);
    }
}
