<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Course;
use App\Models\Frontend\PurchasedCourse;
use App\Models\Frontend\StudentUser;
use App\Submenu;
use Illuminate\Http\Request;

class CoursesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        return Course::paginate(70);
    }

    public function academic()
    {
        $courses = Course::all();
        
        return Course::paginate(9);
    }

    public function forSubmenu(Submenu $submenu)
    {
        return Course::where('category_id', $submenu->id)->paginate(20);
    }

    public function forCategory(Category $category)
    {
        $subs = array();

        foreach($category->submenu as $sub){
            array_push($subs, $sub->id);
        }
        return Course::whereIn('category_id', $subs)->paginate(20);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $id?Course::findorFail($id):json_encode(['error','No course found']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getStudentCourses(StudentUser $student)
    {
        $courses = PurchasedCourse::where('student_id', $student->id)->get();
        return json_encode($courses);
    }
    
}
