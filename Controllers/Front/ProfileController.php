<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends FrontController
{
    public function profileView()
    {
        return view('frontend.profile.index');
    }
    
}
