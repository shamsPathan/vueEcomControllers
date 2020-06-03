<?php

namespace App\Http\Controllers\Front;

use App\Category;
use App\Course;
use App\Models\Frontend\PurchasedCourse;
use App\Models\Frontend\StudentUser;
use App\Section;
use App\Submenu;
use Illuminate\Http\Request;

class CoursesController extends FrontController
{


    public function list()
    {   
        return view('frontend.pages.course.all_course');
    }

    public function academicList()
    {   
        return view('frontend.pages.course.all_academic_course');
    }

    public function submenuCourse (Submenu $submenu)
    {
        return view('frontend.pages.course.for_menu', [
            'submenu' => $submenu->id
        ]);   
    }

    public function categoryCourse (Category $category)
    {
    return view('frontend.pages.course.for_category', [
            'category' => $category->id
        ]);
    }

    public function grid()
    {
        return view('frontend.pages.course.all_course2');
    }

    public function course($id)
    {
        $type = (Category::find((Submenu::find((Course::find($id))->category_id??null))->category_id??null))->course_type??null;
        
        $page = ($type == 'academic' ) ? 'detailsAcademic': 'details';

        return view("frontend.pages.course.$page", compact('id'));
    }

    public function addView()
    {
    	return insview('add_course');
    }
    public function lessions(Course $course)
    {
        return view('frontend.pages.course.lessions', ['id' => $course->id]);
    }

    public function getAllVideos(Course $course)
    {
        $sections = Section::where('course_id', $course->id)->get('id');

        $data = array();
        foreach($sections as $section){
            foreach($section->lecture as $lecture){
                array_push($data, $lecture->title);
            }
            
        }
        return $data;
    }

    public function isPurchasedBy(StudentUser $student, Course $course)
    {
        return PurchasedCourse::where('student_id', $student->id)->where('item_id', $course->id)->first()?
        1:
        0;
    }

    public function latestCourse(int $limit=1)
    {
        return Course::take($limit)->OrderBy('id', 'DESC')->get();
    }

    public function getCourseType(Submenu $submenu)
    {
        $sub = Submenu::with('category')->find($submenu->id);
        
        if($sub){
            return json_encode($sub->category->course_type);
        } else {
            return json_encode(404);
        }
    }
}
