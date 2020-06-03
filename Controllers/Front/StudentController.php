<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Frontend\RefferCoupon;
use App\Models\Frontend\StudentUser;
use App\Profile;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function profile()
    {
        return view('frontend.pages.student.profile');
    }

    public function completed()
    {
        $profile = Profile::where('user_id', session('student')->id)->first();

        return $profile->completed();
    }

    public function getPromoCode(StudentUser $student)
    {   
        $code  = RefferCoupon::where('author_id', $student->id)
        ->where('author_type', 'student')->first();

        return $code?
        json_encode(['status'=>'OK','code'=>$code->code]):
        json_encode(['status'=>'404','code'=>null]);
    }
}
