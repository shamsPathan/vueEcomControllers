<?php

namespace App\Http\Controllers\Back;

use Facades\App\Classes\PriceDivision;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PricingDivisionController extends Controller
{
    public function get()
    {
        return json_encode(PriceDivision::getPercentage());
        
    }

    public function save(Request $request)
    {
        return json_encode(PriceDivision::save($request));
        
    }
}
