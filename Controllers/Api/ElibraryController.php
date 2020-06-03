<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\ELibraryCategory;
use App\Models\Admin\ElibraryPost;
use Illuminate\Http\Request;

class ElibraryController extends ApiController
{
    public function allBooks(){
        $data = ElibraryPost::with('admin', 'instructor' ,'category')->where('status', '1')->get();
        return json_encode($data);
    }
    public function singleBook($id){
        $data = ElibraryPost::with('admin', 'instructor' ,'category')->find($id);
        return $data;
    }

    public function allCategory(){
        $data = ELibraryCategory::get()->all();
        return $data;
    }

    public function getBook(ElibraryPost $book)
    {
            return json_encode(
                ElibraryPost::with('admin', 'instructor')
                    ->where('status', '1')
                    ->where('id', $book->id)
                    ->first()
                );
    }
}
