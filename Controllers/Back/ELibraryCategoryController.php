<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Admin\ELibraryCategory;
use Illuminate\Http\Request;

class ELibraryCategoryController extends Controller
{
    public function addLibraryCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|unique:e_library_categories'
        ]);
        $data['category_name'] = $request->input('category_name');
        ELibraryCategory::create($data);
        return $data;   
    }
    public function showElibraryCategory()
    {
        $data = ELibraryCategory::get()->all();
        return $data;
    }

    public function updateElibraryCategory(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|unique:e_library_categories'
        ]);
        $data['category_name'] = $request->input('category_name');
        ELibraryCategory::findOrFail($id)->update($data);
        return $data;
    }
}
