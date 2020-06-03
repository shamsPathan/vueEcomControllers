<?php

namespace App\Http\Controllers\Back;

use App\CourseInfo;
use App\Models\Admin\AdminUser;
use App\Models\Admin\InstructorUser;
use App\Models\Frontend\RefferCoupon;
use App\Models\Frontend\StudentUser;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
class DataController extends BackController
{
    
    public function adminList(){
        $data = AdminUser::get()->skip(1);
        return $data;
    }
    public function instructors()
    {
        $data = InstructorUser::paginate(10);
        return $data;
    }

    public function courses(){
        $data = CourseInfo::with('submenu', 'pricing', 'media', 'instructor')->orderBy('status', 'ASC')->paginate(10);
        return $data;
    }
    public function students(){
        $data = StudentUser::with('profile','coupon')->paginate(10);
        return $data;
    }
    public function searchCategory(Request $request)
    {
        if ($search = $request->get('q')) {
            $instructor = Category::with('submenu')->where(function($query) use ($search){
                $query->where('title','LIKE',"%$search%")
                        ->orWhere('course_type','LIKE',"%$search%");
            })->get();
        }else{
            $instructor = Category::with('submenu')->get()->all();
        }

        return $instructor;
    }
    public function searchIns(Request $request){

        if ($search = $request->get('q')) {
            $instructor = InstructorUser::where(function($query) use ($search){
                $query->where('first_name','LIKE',"%$search%")
                        ->orWhere('email','LIKE',"%$search%");
            })->get();
        }else{
            $instructor = InstructorUser::paginate(10);
        }

        return $instructor;

    }

    public function searchCourse(Request $request){

        if ($search = $request->get('q')) {
            $course = CourseInfo::with('submenu', 'pricing', 'media', 'instructor')->where(function($query) use ($search){
                $query->where('course_title','LIKE',"%$search%")
                        ->orderBy('course_title', 'desc');
                        })->get();
        }else{
            $course = CourseInfo::with('submenu', 'pricing', 'media', 'instructor')->orderBy('status', 'ASC')->paginate(10);
        }

        return $course;

    }

    public function searchStudent(Request $request){
        if ($search = $request->get('q')) {
            $student = StudentUser::with('profile', 'coupon')->where(function($query) use ($search){
                $query->where('username','LIKE',"%$search%")
                        ->orWhere('email', 'Like', "%$search%");
                        })->get();
        }else{
            $student = StudentUser::with('profile','coupon')->paginate(10);
        }

        return $student;        
    }


    public function coursePreview($id)
    {
        $data = CourseInfo::find($id);
        return view('backend.courseApprove', compact('data'));
    }
    public function courseDataById($id)
    {
        $data = CourseInfo::with('submenu', 'pricing', 'media', 'instructor', 'section')->find($id);
        return $data;
    }

    public function approveCourse($id)
    {
        $data = CourseInfo::find($id);
        if($data->status == 0 || $data->status == 2){
            $data->status = 1;
        } else {
            return 'false';
        }
        $data->save();
        return $data;
    }
    public function rejectCourse($id)
    {
        $data = CourseInfo::find($id);
        if($data->status == 0 || $data->status == 1){
            $data->status = 2;
        } else {
            return 'false';
        }
        $data->save();
        return $data;
    }
    public function deleteCourse($id){

        CourseInfo::findOrFail($id)->delete();
        return response()->json(['success' => 'Course Deleted Successfully']);
    }

    public function deleteInstructor($id){
        InstructorUser::findOrFail($id)->delete();
        return json_encode(['success' => 'Instructor Deleted Successfully']);
    }

    public function deleteStudent($id){
        StudentUser::findOrFail($id)->delete();
        return json_encode(['success' => 'Student Deleted Successfully']);   
    }
    public function makeCoupon(StudentUser $student)
    {
        $coupon = RefferCoupon::where('author_id', $student->id)
        ->where('author_type', 'student')
        ->latest()->first();

        $prefix = 'R'.rand(0, 9);
        $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = $prefix.substr(str_shuffle($string), rand(0, 20), 6);
        if(!$coupon){
          $coupon = new RefferCoupon;
          $coupon->author_id = $student->id;
          $coupon->author_type = 'student';
          $coupon->code = $code;
          $coupon->expire_date = Carbon::now()->add(1, 'day');
          $coupon->save();

        }
        return json_encode(["status"=>'OK']);
    }

    public function promoCodes()
    {
        $codes = StudentUser::with('coupon')->get();
        return json_encode(['codes'=> $codes]);
    }

    public function saveCoupon(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $coupon = RefferCoupon::find($request->id);
        if(!$request->code){
            $coupon->delete();
            return json_encode('destroyed');
        }
        $coupon->update($request->all());
        return json_encode($coupon);

    }
}
