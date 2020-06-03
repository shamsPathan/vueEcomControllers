<?php

namespace App\Http\Controllers\Back;

use Illuminate\Http\Request;
use App\Http\Requests\SettingsRequest;
use App\Settings;
use App\SettingsImage;

class SettingsController extends BackController
{
	
	public function settingsView()
	{
		$data['settings_images'] = SettingsImage::get()->first();
		$data['settings'] = Settings::get()->first();
		return saView('settings.index', $data);
	}
	public function settingsUpdate(SettingsRequest $request)
	{
		$data['website_name'] 			= $request->input('website_name');
		$data['website_title'] 			= $request->input('website_title');
		$data['email'] 					= $request->input('email');
		$data['phone'] 					= $request->input('phone');
		$data['address'] 				= $request->input('address');
		$data['website_keywords'] 		= $request->input('website_keywords');
		$data['author'] 				= $request->input('author');
		$data['website_description'] 	= $request->input('website_description');
		$datum = Settings::first();
		if (!$datum) {
			Settings::create($data);
		} else {
			$datum->update($data);
		}
		return redirect()->back();
	}
	public function settingsUploads(Request $request)
	{
		$request->validate([
			'website_logo' => 'image',
			'banner_image' => 'image'
		]);

		// $data['website_logo'] = null;
		if ($request->hasFile('website_logo'))
		{
			$filename 					= $request->file('website_logo');
			$extension 					= $filename->getClientOriginalExtension();
			$filenameToStore 			= rand(10000, 99999).'.'.$extension;
			$path 						= $request->file('website_logo')->storeAs('public/settings/',$filenameToStore);
			$data['website_logo'] 		= $filenameToStore;
		}
		if ($request->hasFile('banner_image'))
		{
			$filename 					= $request->file('banner_image');
			$extension 					= $filename->getClientOriginalExtension();
			$filenameToStore 			= rand(10000, 99999).'.'.$extension;
			$path 						= $request->file('banner_image')->storeAs('public/settings/',$filenameToStore);
			$data['banner_image'] 		= $filenameToStore;
		}
		$datum = SettingsImage::first();
		if ($datum)
		{
			$datum->update($data);
		} else {
			SettingsImage::create($data);
		}
		return redirect()->back();
	}
}
