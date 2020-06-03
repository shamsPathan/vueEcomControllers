<?php

namespace App\Http\Controllers\Front;

use App\BlogsComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\BlogPost;

class BlogsCommentController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'blog_id' => 'required',
            'email'   => 'required',
            'message' => 'required'
        ]);
        
        $old = BlogsComment::where('blog_id', $request->blog_id)->where('student_id', session('student')->id)->first();
        
        if($old) return '301';
        
        $blog = new BlogsComment;
        $blog->student_id = session('student')->id;
        $blog->blog_id    = $request->blog_id;
        $blog->name       = $request->name??null;
        $blog->email      = $request->email;
        $blog->website    = $request->website??null;
        $blog->message    = $request->message;
        $blog->save();
        
        return $blog;
    }

    public function comments(BlogPost $blog)
    {
        return BlogsComment::where('blog_id', $blog->id)->with('author')->get();
    }
}
