<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Institute;
use Illuminate\Http\Request;

class InstituteController extends ApiController
{
    public function index()
    {
        return json_encode(Institute::all());
    }
}
