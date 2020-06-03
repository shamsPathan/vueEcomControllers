<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Frontend\Order;

class OrdersController extends Controller
{

    private $model = null;

    public function __construct()
    {
        $this->model = new Order;
    }

    public function orders()
    {
        $orders = $this->model->with('transaction', 'coupon', 'buyer', 'course')->get();
        return json_encode($orders);

    }
}
