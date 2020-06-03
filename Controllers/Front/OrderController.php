<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Order;
use App\Classes\Cart;
use App\Models\Frontend\StudentUser;

class OrderController extends Controller
{
    public function checkoutOnline(Request $request)
    {

        $cart = session()->get('cart');

        if(!$cart){
            return redirect('/')->with('Please add something before you order');
        }
     
        $user = $request->user;

        if(!$user){
            return redirect('/shopping_cart')->with('error', 'Please sign in');
        }
        
        $buyer = StudentUser::find($user);
        
        if(!$buyer){
            return redirect('/shopping_cart')->with('error', 'Something wrong with your account');
        }


        $cart = new Cart($cart);

        return (new Order($cart, $buyer))->place();
    }

}
