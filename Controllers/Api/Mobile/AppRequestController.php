<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Category;
use App\Course;
use App\Http\Controllers\Api\CoursesController;
use App\Models\Admin\ELibraryCategory;
use App\Models\Admin\ElibraryPost;
use App\Models\Frontend\StudentUser;
use App\Submenu;

class AppRequestController extends MobileAppController
{
    public function __construct()
    {
        parent::__construct();
        $this->api = new CoursesController();
    }

    public function courses()
    {
        return Course::all();
    }

    public function coursesByCat(Category $category)
    {
        $subs = array();

        foreach($category->submenu as $sub){
            array_push($subs, $sub->id);
        }

        return Course::whereIn('category_id', $subs)->get();
    }

    public function coursesBySubcat(Submenu $subcat)
    {
        return Course::where('category_id', $subcat->id)->get();
    }

    public function course(Course $course)
    {
        return $this->api->show($course->id);
    }

    public function profileData()
    {
        return $this->getUser(request()->all());
    }

    public function categories()
    {
        return Category::with('submenu')->get();
    }

    public function books()
    {
        return ElibraryPost::all();
    }

    public function bookCategories()
    {
        return ELibraryCategory::all();
    }

    public function booksByCat( ELibraryCategory $cat )
    {
        return ElibraryPost::where('category_id' , $cat->id)->get();
    }

    private function getUser(array $data)
    {
        return isset($data['email']) ? StudentUser::select('id','email','phone')->with('profile')->where('email', $data['email'])->first() :
            isset($data['phone']) ? StudentUser::select('id','email','phone')->with('profile')->where('phone', $data['phone'])->first() :
            null;
    }

}
