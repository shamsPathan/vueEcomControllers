<?php

namespace App\Http\Controllers\Back;

use App\Models\Admin\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends BackController
{
    public function addCategory(Request $request){
        $request->validate([
            'category_name' => 'required|unique:blog_categories',
        ]);
        $data['category_name'] = $request->input('category_name');
        BlogCategory::create($data);
        return $data;
    }

    public function allBlogCategory()
    {
        return BlogCategory::all();
    }
    public function updateBlogCategory(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|unique:blog_categories',
        ]);
        $data['category_name'] = $request->input('category_name');
        BlogCategory::findOrFail($id)->update($data);
        return $data;
    }
}
