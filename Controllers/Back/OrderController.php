<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Frontend\Order;
use App\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function approve(Order $order)
    {
         $order->approved = true;

         if($order->save()){
              $trans  = Transaction::find($order->transaction_id);
              $trans->approved = true;
              $trans->save();
         }
         return json_encode(true);
    }

    public function freez(Order $order)
    {
     $order->approved = false;

     if($order->save()){
          $trans  = Transaction::find($order->transaction_id);
          $trans->approved = false;
          $trans->save();
     }
     return json_encode(true);
    }
}
