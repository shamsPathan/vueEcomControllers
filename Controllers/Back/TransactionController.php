<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new Transaction;
    }
    
    public function all()
    {
        return json_encode($this->model->with('course','order')->get());
    }
}
