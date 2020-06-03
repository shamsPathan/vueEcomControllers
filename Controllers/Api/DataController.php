<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController extends ApiController
{
    public function getDivisions()
    {
        $divisions = DB::table('divisions')->get();
        return json_encode($divisions);
    }

    public function getDistctict(int $division)
    {
        $dis = DB::table('districts')->where('division_id', (int)$division)->get();

        return json_encode($dis);
    }

    public function getBloodGroups()
    {
        $groups = DB::table('blood_group')->get();
        return json_encode($groups);
    }

    public function getCategories()
    {
        return Category::all();
    }

    public function getAcademicCategories()
    {
        return Category::allAcademic();
    }

    public function getProfessionalCategories()
    {
        return Category::allProfessional();
    }

}
