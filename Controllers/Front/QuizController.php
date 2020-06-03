<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Lecture;
use Illuminate\Http\Request;
use App\LectureQuiz;
use App\Models\Frontend\PurchasedCourse;
use App\QuestionOption;
use App\StudentQuizResult;
use CreateStudentQuizResults;

class QuizController extends Controller
{
    public function checkAnswer(Request $request)
    {
        $request->validate([
            'question_id' => 'required',
            'answer'      => 'required'
        ]);

        $answer = QuestionOption::where('question_id',$request->question_id)->first();
        
        if($answer){
            if($answer->is_right==$request->answer){
                return json_encode(['right_answer' => true]);
            } else {
                return json_encode(['right_answer' => $request->answer]);
            }
        }   
    }

    public function getQuestion(Request $request)
    {
        $previous = $request->previous;
        $lectureID = $request->lecture;

        $previous = explode(',',$previous);
        
        if (!$previous[0] || $previous[0] == 0) {
            return LectureQuiz::where('lecture_id', $lectureID)->with('options')->get()->random(1)[0];
        }

        $quiz = LectureQuiz::where('lecture_id', $lectureID)->whereNotIn('id', $previous)->first();

        if (!$quiz) {
            return null;
        }

        return LectureQuiz::where('lecture_id', $lectureID)->whereNotIn('id', $previous)->with('options')->get()->random(1)[0];
    }

    public function getQuestions(Lecture $lecture)
    {

        $questions = LectureQuiz::where('lecture_id', $lecture->id)->with('options')->inRandomOrder()->take(5)->get();

	if(count($questions)){
		return $questions;
	} else {
		throw new \Exception('No Questions found');
	}
    }

    public function saveScore(Request $request)
    {

        // check if already exemined

        $request->validate([
            'course_id'   => 'required',
            'section_id'  => 'required',
            'lecture_id'  => 'required',
            'question_id' => 'required',
            'score'       => 'required'
        ]);

        // dd($request->all());
        $result = StudentQuizResult::where('lecture_id',    $request->lecture_id)
                                     ->where('student_id',  session('student')->id)
                                     ->where('question_id', $request->question_id)
                                     ->first();
        if($result){
            return json_encode(['saved', -1]);
        }

        $result = new StudentQuizResult;
        $result->course_id     = $request->course_id;
        $result->section_id    = $request->section_id;
        $result->lecture_id    = $request->lecture_id;
        $result->question_id   = $request->question_id;
        $result->student_id    = session('student')->id;
        $result->score         = (int)$request->score;
        
        if($result->save()){
            return json_encode(['saved', true]);
        } else {
            return json_encode(['saved', false]);
        }
    }

    public function getExams()
    {
        $exams = StudentQuizResult::where('student_id', session('student')->id)->with('course')->get();
        return json_encode($exams);
    }
}
