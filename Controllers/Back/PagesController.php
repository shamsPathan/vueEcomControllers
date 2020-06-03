<?php

namespace App\Http\Controllers\Back;


class PagesController extends BackController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function dashboard()
    {
        return view('backend.admin.master');
    }
}
