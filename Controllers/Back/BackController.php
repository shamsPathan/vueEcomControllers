<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class BackController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $thisClass = get_class($this);

            if ($thisClass == 'App\Http\Controllers\Back\InstructorController') {
                if (!session('instructor')) {
                    return redirect('/instructor-login');
                }
            } elseif ($thisClass == 'App\Http\Controllers\Back\PagesController') {
                if (!session('user')) {
                    return redirect('/sadmin');
                }
            }
            return $next($request);
        });
    }
}
