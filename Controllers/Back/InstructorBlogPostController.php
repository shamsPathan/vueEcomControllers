<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\BlogPost;
use Image;
class InstructorBlogPostController extends BackController
{
    public function index()
    {
    	
    	return view('frontend.instructor.pages.blogpostlist');
    }
    public function blogByinstructor()
    {
    	$id 	= session()->get('instructor')->id;
    	$data 	= BlogPost::with('admin', 'instructor', 'category')->where('ins_id', $id)->get();

    	return $data;
    }
    public function blogPostAdd()
    {
    	return view('frontend.instructor.pages.blogpostadd');
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
