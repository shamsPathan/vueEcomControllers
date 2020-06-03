<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Admin\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Image;
class BlogPostController extends Controller
{
    public function allBlogs(){
        $data = BlogPost::with('admin', 'instructor', 'category')->get()->all();
        return $data;
    }
    public function addBlogPost(Request $request)
    {

    	$data['category_id']    = $request->category_id;
    	$data['blog_title']     = $request->blog_title;
        $data['blog_details']   = $request->blog_details;
        $data['status']         = '1';
        $data['admin_id']       = session()->get('user')->id;

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
        return $data;
    }
    public function editBlogPost($id){
        $data = BlogPost::find($id);

        return $data;
    }
    public function updateBlogPost(Request $request, $id){

        $data['category_id']    = $request->category_id;
        $data['blog_title']     = $request->blog_title;
        $data['blog_details']   = $request->blog_details;
        $data['status']         = '1';
        $data['admin_id']       = session()->get('user')->id;

        $currentBlogPost        = BlogPost::where('id', $id)->first();
        $currentImage           = $currentBlogPost->featured_image;

        if ($request->featured_image != $currentImage) 
        {

            if ($request->featured_image) {
        $image                  = $request->featured_image;  // your base64 encoded
        $str                    = $image;
        $imageName              = time().'.'.explode('/', explode(':', substr($str, 0, strpos($str, ';')))[1])[1];
        $path                   = 'storage/blog/thumb/';
            if (!is_dir($path)) {
                mkdir($path,0777,true);
            }
            Image::make($image)->save($path.$imageName);
            $data['featured_image'] = $imageName;
            }
        }
        
        BlogPost::findOrFail($id)->update($data);
        return $data;
    }
}
