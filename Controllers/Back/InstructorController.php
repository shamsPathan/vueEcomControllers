<?php

namespace App\Http\Controllers\Back;

use App\Category;
use App\LessionMaterials;
use App\CourseInfo;
use App\CourseMedia;
use App\CoursePricing;
use App\Lecture;
use App\Models\Admin\InstructorUser;
use App\Submenu;
use Illuminate\Http\Request;

class InstructorController extends BackController
{
    public function __construct(CourseInfo $course_info, CoursePricing $course_pricing, CourseMedia $course_media)
    {
        parent::__construct();
        $this->course_info      = $course_info;
        $this->course_pricing   = $course_pricing;
        $this->course_media     = $course_media;
    }
    public function showPage()
    {
        return view('frontend.instructor.dashboard');
    }
    public function allCourses()
    {
        return insview('index');
    }

    public function courseMaterials()
    {
        return insview('courseMaterials');
    }

    public function addMaterials(CourseInfo $course = null)
    {
        if($course){
            return insview('add_materials', ['sections' => $course->section]);
        } else {
            $material = new LessionMaterials();
            $material->lecture_id = request()->lession;
            $material->text = request()->text;

            // course materials pdf
            if(request()->pdf) {
                $filename = request()->pdf;
                $folder = 'storage/course/materials';
                if(!file_exists($folder))
                    if (!mkdir($folder, 0777, true)) {
                        die('Failed to create folders...');
                    }
                $extension = $filename->getClientOriginalExtension();
                $filenameTostore = rand(10, 1000).'-'.time().'.'.$extension;
                \Storage::disk('pdf')->put($filenameTostore, file_get_contents($filename));
                $material->pdf = $filenameTostore;
            }
            
            $material->save();
            return redirect()->route('course.materials');
        }
        
    }

    public function index()
    {
        $id = session()->has('instructor') ? session()->get('instructor')->id : null;
        $courses = CourseInfo::with('submenu', 'pricing', 'media', 'instructor', 'section')->where(['ins_id'=> $id])->paginate(10);
        return $courses;
    }
    public function addView()
    {
        $categories = Category::with('submenu')->get();
        return insview('add_course', compact('categories'));
    }
    public function store(Request $request)
    {
        
        $this->course_info->course_title 			= $request->input('course_title');
        $this->course_info->short_desc 				= $request->input('short_desc');
        $this->course_info->course_description 		= $request->input('course_description');
        $this->course_info->experience 				= $request->input('experience');
        $this->course_info->language 				= $request->input('language');
        $this->course_info->category_id 			= $request->input('category_id');
        $this->course_info->meta_keywords           = $request->input('meta_keywords');
        $this->course_info->meta_desc               = $request->input('meta_desc');
        $this->course_info->requirements            = json_encode($request->input('requirements'));
        $this->course_info->outcomes                = json_encode(($request->input('outcomes')));
        $this->course_info->ins_id                  = session()->get('instructor')->id;
        $this->course_info->save();

        $this->course_pricing->course_id            = $this->course_info->id;
        $this->course_pricing->course_price         = $request->input('course_price');
        $this->course_pricing->discount_price       = ($request->input('discount_price')/$request->input('course_price'))*100;
        $this->course_pricing->save();


        $this->course_media->course_id              = $this->course_info->id;
        if($request->hasFile('overview_video')) {
            $filename = $request->file('overview_video');
            $extension = $filename->getClientOriginalExtension();
            $filenameTostore = rand(10, 1000).'-'.time().'.'.$extension;
            $path = $filename->storeAs('public/course/videos/',$filenameTostore);
            $this->course_media->overview_video     = $filenameTostore;
        }

        if($request->hasFile('course_thumbnail')) {
            $filename = $request->file('course_thumbnail');
            $extension = $filename->getClientOriginalExtension();
            $filenameTostore = rand(10, 1000).'-'.time().'.'.$extension;
            $path = $filename->storeAs('public/course/thumb/',$filenameTostore);
            $this->course_media->course_thumbnail   = $filenameTostore;
        }
        $this->course_media->video_url              = $request->input('video_url');
        $this->course_media->save();
        
        return redirect()->route('instructor.view');

    }

    public function editCourseName()
    {
        $course = CourseInfo::find((int)request()->post('course_id'));
        $course->course_title = request()->post('course_title');
        $course->update();
        return $course;
    }

    public function sectionView($id)
    {
        return insview('section.create', compact('id'));
    }
    public function sectionViewById($id)
    {
        $data = CourseInfo::with('submenu', 'pricing', 'media', 'section')->find($id);
        return $data;
    }
    public function profile(){
        return view('frontend.instructor.pages.profile');
    }
    public function profileInfo()
    {
        return session()->get('instructor');
    }
}
