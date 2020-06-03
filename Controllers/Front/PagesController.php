<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Category;
use App\Models\Admin\BlogCategory;
use App\Models\Admin\BlogPost;
use Illuminate\Support\Facades\Session;

class PagesController extends FrontController
{
    public function __construct()
    {
        $academic_courses    = Category::where('course_type', '=', 'academic')->with('submenu')->get()->toJson();
        $premium_courses     = Category::where('course_type', '=', 'premium')->with('submenu')->get()->toJson();

        \View::share(compact('academic_courses', 'premium_courses'));
    }
    public function Home()
    {
        // dd(session('student'));
        return view('frontend.pages.welcome');
    }

    public function Blogs()
    {
        $blogs = BlogPost::with('instructor','admin')->get();
        return view('frontend.pages.blog',[
            'blogs' => $blogs
        ]);
    }

    public function adminLogin()
    {
        if(session('user')){
            return redirect('/dashboard');
        }
        return view('frontend.pages.login.admin');
    }

    public function instructorLogin()
    {
        return view('frontend.pages.login.instructor');
    }

    public function shoppingCart()
    {
        return view('frontend.pages.shoppingCart', ['cart'=>json_encode(session()->get('cart')??null)]);
    }

    public function payment()
    {
        return view('frontend.pages.payment',['cart'=>json_encode(session()->get('cart'))]);

    }

    private function getCartIds()
    {
        $items = array();
        if(Session::has('cart')){
            $cart = Session::get('cart');
            foreach($cart as $index=>$item){
                array_push($items, $index);
            }
        }
        return $items;

    }

    public function latestBlogs($limit=5)
    {
        return BlogPost::take($limit)->orderBy('id','DESC')->get();
    }

    public function blogDetails(string $param)
    {
        $blog = BlogPost::where('id', $param)
            ->orWhere('slug', $param)
            ->firstOrFail();
        return view('frontend.pages.blog-details',['blog'=> $blog]);
    }

    public function blogCategories()
    {
        return BlogCategory::all();
    }

    public function blogsCount(BlogCategory $category)
    {
        $blogs = BlogPost::where('category_id',$category->id)->get();
        return count($blogs);
    }
    

}
