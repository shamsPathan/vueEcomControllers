<?php

namespace App\Http\Controllers\Front;

use App\Course;
use App\CourseRating;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseRatingController extends Controller
{
    public function rate(Course $course, Request $request)
    {
        $data = $request->validate([
            'rating' => 'required',
            'comment' => 'required',
            'user'    => 'required'
        ]);
        $rating = new CourseRating;
        
        $rating->user_id        = (int)$data['user'];
        $rating->course_id      = (int)$course->id;
        $rating->rating         = (int)$data['rating'];
        $rating->comment        = $data['comment'];
        $rating->save();

        return $rating ?
            json_encode(['status' => 200, 'feedback'  => CourseRating::find($rating->id)])
            : json_encode(['status' => 204, 'feedback' => "Rating couldn't be posted!"]);
    }
}
