<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
   	public function index()
   	{
   		return view('frontend.instructor.pages.blogpostlist');
   	}
}
