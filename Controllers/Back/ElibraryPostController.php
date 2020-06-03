<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Admin\ElibraryPost;
use Illuminate\Http\Request;
use Image;
use Illuminate\Support\Str;

class ElibraryPostController extends Controller
{
    public function allBooks(){
      $data = ElibraryPost::get()->all();
      return $data;
    }

    public function editBook(Request $request)
    {
      $data['category_id']    = $request->category_id;
      $data['book_title']     = $request->book_title;
      $data['sell_price']     = $request->price;
      $data['status']         = '1';

      if($request->cover_image){
      // String to image convertion for cover image 
      $name = uniqid().'.' . explode('/', explode(':', substr($request->cover_image, 0, strpos($request->cover_image, ';')))[1])[1];
      Image::make($request->cover_image)->save(storage_path('app/public/book/').$name);
      $request->merge(['cover_image' => $name]);
      $data['cover_image']    = $request->cover_image;
    }

   
    if($request->get('read_some_pdf')){
      // string to pdf convertion for read some pages of books 
      $b64 = $request->get('read_some_pdf');
      $pdfparts = explode(";base64,", $b64);
      $image_type_aux = explode("application/", $pdfparts[0]);
      $image_type = $image_type_aux[1];

      // return $pdfparts;
      $image_base64 = base64_decode($pdfparts[1]);
      $readPdfName = uniqid() . '.'.$image_type;

      $path = 'storage/book/'.$readPdfName;
      # Write the PDF contents to a local file
      file_put_contents($path, $image_base64);
      $data['read_some_pdf']  = $readPdfName;

    }

    if($request->get('full_book')){
      // String to pdf converstion for full book
      $fullbookB64  = $request->get('full_book');
      $bookPdfParts = explode(";base64,", $fullbookB64);
      $pdf_type_aux = explode("application/", $bookPdfParts[0]);
      $app_type     = $pdf_type_aux[1];

      // return pdf parts for decode 
      $fullbookpdf  = base64_decode($bookPdfParts[1]);
      $bookname = Str::slug($request->book_title) . '.'. $app_type;

      $fullbookpath = 'storage/book/'.$bookname;
      
      file_put_contents($fullbookpath, $fullbookpdf);
      $data['full_book']      = $bookname;
    }

      $book = ElibraryPost::find($request->id);
      $book->update($data);    
      return $data;

    }


    public function addBook(Request $request)
    {
      
      // String to image convertion for cover image 
      $name = uniqid().'.' . explode('/', explode(':', substr($request->cover_image, 0, strpos($request->cover_image, ';')))[1])[1];

      Image::make($request->cover_image)->save(storage_path('app/public/book/').$name);
      $request->merge(['cover_image' => $name]);

      // string to pdf convertion for read some pages of books 
      $b64 = $request->get('read_some_pdf');
      $pdfparts = explode(";base64,", $b64);
      $image_type_aux = explode("application/", $pdfparts[0]);
      $image_type = $image_type_aux[1];

      // return $pdfparts;
      $image_base64 = base64_decode($pdfparts[1]);
      $readPdfName = uniqid() . '.'.$image_type;

      $path = 'storage/book/'.$readPdfName;
      # Write the PDF contents to a local file

      \Storage::disk('pdf')->put($readPdfName, file_get_contents($b64));

      // String to pdf converstion for full book
      $fullbookB64  = $request->get('full_book');
      $bookPdfParts = explode(";base64,", $fullbookB64);
      $pdf_type_aux = explode("application/", $bookPdfParts[0]);
      $app_type     = $pdf_type_aux[1];

      // return pdf parts for decode 
      $bookname = Str::slug($request->book_title) . '.'. $app_type;
      \Storage::disk('pdf')->put($bookname, file_get_contents($fullbookB64));
      // file_put_contents($fullbookpath, $fullbookpdf);

      $data['category_id']    = $request->category_id;
      $data['book_title']     = $request->book_title;
      $data['cover_image']    = $request->cover_image;
      $data['sell_price']     = $request->price;
      $data['status']         = '1';
      $data['read_some_pdf']  = $readPdfName;
      $data['full_book']      = $bookname;
      $data['admin_id']       = session()->get('user')->id??1;

      ElibraryPost::create($data);
      return $data;

    }

    public function changeStatus($id){
      $data = ElibraryPost::find($id);
      if($data->status == 0){
        $data->status = 1;
      } elseif($data->status == 1) {
        $data->status = 0;
      }
      $data->save();
      return $data;
    }
}
