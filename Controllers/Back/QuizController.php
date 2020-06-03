<?php

namespace App\Http\Controllers\Back;

use App\LectureQuiz;
use App\QuestionOption;
use Illuminate\Http\Request;

class QuizController extends BackController
{
    public function __construct(LectureQuiz $lectureQuiz, QuestionOption $questionOption)
    {
        parent::__construct();
        $this->lectureQuiz = $lectureQuiz;
        $this->questionOption = $questionOption;
    }

    public function addQuiz(Request $request){

        $request->validate([
            'question' => 'required',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required'
        ]);

        $this->lectureQuiz->lecture_id = $request->input('lecture_id');
        $this->lectureQuiz->question   = $request->input('question');
        $this->lectureQuiz->save();

        $this->questionOption->question_id  = $this->lectureQuiz->id;
        $this->questionOption->option_a     = $request->input('option_a');
        $this->questionOption->option_b     = $request->input('option_b');
        $this->questionOption->option_c     = $request->input('option_c');
        $this->questionOption->option_d     = $request->input('option_d');
        $this->questionOption->is_right     = implode(' ', $request->input('is_right'));
        $this->questionOption->save();

        return $this->lectureQuiz.$this->questionOption;
    }
}
