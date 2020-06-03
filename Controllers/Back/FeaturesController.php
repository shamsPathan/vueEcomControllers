<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Features;
use Str;
class FeaturesController extends Controller

{
    public function allFeatures(){
        $data = Features::get()->all();
        return $data;
    }
    public function addFeatures(Request $request)
    {
    	$request->validate([
    		'title' => 'required',
    		'details' => 'required',
    		'icon' => 'required',
    	]);

    	$data['title'] 		= $request->input('title');
    	$data['details'] 	= $request->input('details');
    	$b64                = $request->get('icon');
        $iconparts         	= explode(";base64,", $b64);
        $icon_type         = explode("image/", $iconparts[0]);
        $icon_type_main    = $icon_type[1];

        $icon_base64       = base64_decode($iconparts[1]);
        $iconname          = Str::slug($request->input('title')) .'-'.rand(). '.'.$icon_type_main;

        $path = 'storage/icon/';
        if (!is_dir($path)) {
            mkdir($path,0777,true);
        }
        # Write the Video contents to a local file
        file_put_contents($path.$iconname, $icon_base64);

        $data['icon'] 		= $iconname;
    	
    	Features::create($data);

    	return $data;
    }

    public function updateFeatures(Request $request, $id){

        $data['title']      = $request->input('title');
        $data['details']    = $request->input('details');

        $currentFeature     = Features::where('id', $id)->first();
        $currentImage       = $currentFeature->icon;

        if ($request->icon != $currentImage) 
        {
            if($request->icon){

            $b64                = $request->get('icon');
            $iconparts          = explode(";base64,", $b64);
            $icon_type          = explode("image/", $iconparts[0]);
            $icon_type_main     = $icon_type[1];

            $icon_base64        = base64_decode($iconparts[1]);
            $iconname           = Str::slug($request->input('title')) .'-'.rand(). '.'.$icon_type_main;

            $path = 'storage/icon/';
            if (!is_dir($path)) {
                mkdir($path,0777,true);
            }
            # Write the Video contents to a local file
            file_put_contents($path.$iconname, $icon_base64);

            $data['icon']       = $iconname;
            }
        }
        Features::findOrFail($id)->update($data);

        return $data;
    }
}
