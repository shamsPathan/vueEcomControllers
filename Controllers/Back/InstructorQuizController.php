<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\BlogPost;
use Image;
class InstructorQuizController extends BackController
{
    public function list()
    {
    	
    	return view('frontend.instructor.pages.blogpostlist');
    }
    public function setting()
    {

    	return view('frontend.instructor.pages.quize-setting');
    }
    public function questions()
    {
    	return view('frontend.instructor.pages.quize-questions');
    }
    
    public function blogPostStore(Request $request)
    {
    	$data['category_id']    = $request->category_id;
    	$data['blog_title']     = $request->blog_title;
        $data['blog_details']   = $request->blog_details;
        $data['status']         = '1';
        $data['ins_id']       	= session()->get('instructor')->id;

        $image                  = $request->featured_image;  // your base64 encoded
        $str                    = $image;
        $imageName 				= time().'.'.explode('/', explode(':', substr($str, 0, strpos($str, ';')))[1])[1];
        $path                   = 'storage/blog/thumb/';
        if (!is_dir($path)) {
            mkdir($path,0777,true);
        }
        Image::make($image)->save($path.$imageName);
        $data['featured_image'] = $imageName;

        BlogPost::create($data);
        return redirect()->back();
    }
    public function blogPostEdit($id){
    	$data 	= BlogPost::find($id);
    	return view('frontend.instructor.pages.blogpostedit', compact('data'));
    }
}
