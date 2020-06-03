<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin\ElibraryPost;
use Illuminate\Http\Request;

class ElibraryController extends FrontController
{
    public function index(){
        return view('frontend.pages.ebook.view');
    }
    public function singleBook($id){
        $data = ElibraryPost::find($id);
        return view('frontend.pages.ebook.details', compact('data'));
    }
}
