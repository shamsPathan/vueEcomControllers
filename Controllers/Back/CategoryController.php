<?php

namespace App\Http\Controllers\Back;

use Illuminate\Http\Request;
use App\Category;
use App\Submenu;
class CategoryController extends BackController
{
	public function allCategories()
	{
		$data = Category::with('submenu')->get()->all();
		return $data;
	}
	public function childCategories()
	{
		$submenu = Submenu::with('category')->paginate(10);
		return $submenu;
	}
    public function createView()
	{
		$data['categories'] = Category::get()->all();
		return saView('category.create', $data);
	}
	public function categoryStore(Request $request)
	{
		$data['course_type'] 			= $request->input('course_type');
		$data['title'] 					= $request->input('title');
		$data['icon']                   = $request->input('icon');

		if ($request->input('category_id') == "0")
		{
			Category::create($data);
		} else {
			$data['category_id'] = $request->input('category_id');
			Submenu::create($data);
		}
		return $data;
	}
	public function categoryEdit($id)
	{
		$data = Category::find($id);
		return $data;
	}
	public function categoryUpdate(Request $request, $id)
	{
		$data['course_type'] 			= $request->input('course_type');
		$data['title'] 					= $request->input('title');
        $data['icon']                   = $request->input('icon');

		Category::findOrFail($id)->update($data);
		return $data;
	}
	public function categoryDelete($id)
	{
		Category::findOrFail($id)->delete();
		return json_encode(['success' => 'Category Deleted Successfully']);
	}

	public function submenuEdit($id)
	{

		$data = Submenu::find($id);
		return $data;

	}
	public function submenuUpdate(Request $request, $id)
	{
		$data['category_id'] 			= $request->input('category_id');
		$data['title'] 					= $request->input('title');

		Submenu::findOrFail($id)->update($data);
		return $data;
	}
	public function deleteSubmenu($id)
	{
		Submenu::findOrFail($id)->delete();

		return json_encode(['success' => 'Submenu Deleted Successfully']);
	}
}
