<?php

namespace App\Http\Controllers\Back;

use App\Course;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lecture;
use App\LectureProgress;

class LectureController extends Controller
{
    public function addLecture(Request $request){
        $request->validate([
            'title' => 'required',
            'lecture_video' => 'required',
        ]);
        $data['title']      = $request->input('title');
        $data['section_id'] = $request->input('section_id');
        $data['summary']    = $request->input('summary');
        $data['length']     = $request->input('length');

        // string to pdf convertion for read some pages of books
        $b64                = $request->get('lecture_video');
        $videoparts         = explode(";base64,", $b64);
        $video_type         = explode("video/", $videoparts[0]);
        $video_type_main    = $video_type[1];

        $video_base64       = base64_decode($videoparts[1]);
        $videoName          = date('Y').'/'.date('F').'/'.Str::slug($request->input('title')) .'-'.rand(). '.'.$video_type_main;

        $path = 'storage/lectures/';
        if (!is_dir($path)) {
            mkdir($path,0777,true);
        }
        # Write the Video contents to a local file
        file_put_contents($path.$videoName, $video_base64);

        $data['lecture_video'] = $videoName;

        Lecture::create($data);
        return $data;
    }
    public function editlecture($id){
        $data = Lecture::find($id);
        return $data;
    }
    public function update(Request $request, $id){

        $data['title']      = $request->input('title');
        $data['section_id'] = $request->input('section_id');
        $data['summary']    = $request->input('summary');
        $data['length']     = $request->input('length');
        
        if($request->lecture_video != null){

        $b64                = $request->get('lecture_video');
        $videoparts         = explode(";base64,", $b64);
        $video_type         = explode("video/", $videoparts[0]);
        $video_type_main    = $video_type[1];
        $video_base64       = base64_decode($videoparts[1]);
        $videoName          = date('Y').'/'.date('F').'/'.Str::slug($request->input('title')) .'-'.rand(). '.'.$video_type_main;
        $path               = 'storage/lectures/';
        file_put_contents($path.$videoName, $video_base64);
        $data['lecture_video'] = $videoName;

        }
        Lecture::find($id)->update($data);
        return $data;

    }

    public function makeDone(Lecture $lecture, Request $request)
    {
        $student = session('student');
        
        if($student){
            
            $lecture->makeSeenBy($student->id);

            return json_encode(['OK'=> 200]);
        } else {
            return json_encode(['OK'=> 404]);
        }
        

        
    }

    public function getDone(Course $course)
    {
        $student = session('student');
        
        if(!$student) return json_encode(['status'=>404, 'msg' => "Please login"]);

        $sections = $course->sections;
        $lectures = [];
        foreach($sections as $section){
            foreach($section->lecture as $lecture){
                array_push($lectures, $lecture->id);
            }
        }

        $lecturesDone = [];

        foreach($lectures as $lecture){
            $seen = LectureProgress::where('student_id', $student->id)->where('lecture_id', $lecture)->first();
            
            if($seen){
                array_push($lecturesDone, $lecture);
            }
        }
        return json_encode(['status'=> 200,'lectures' => $lecturesDone]);
    }
}
