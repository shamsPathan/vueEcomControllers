<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Section;
use Illuminate\Http\Request;

class SectionController extends BackController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'section_name'=> 'required',
            'course_id' => 'required'
        ]);
        $data['section_name'] = $request->input('section_name');
        $data['course_id'] = $request->input('course_id');
        Section::create($data);
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data['section_name'] = $request->input('section_name');
        $data['course_id'] = $request->input('course_id');
        Section::findOrFail($id)->update($data);
        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        //
    }
}
