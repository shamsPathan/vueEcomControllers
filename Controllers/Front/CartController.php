<?php

namespace App\Http\Controllers\Front;

use App\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Helpers\CouponChecker;
use App\Classes\DiscountCoupon;
use App\Classes\RefferCoupon;
use App\Models\Admin\ElibraryPost;

class CartController extends Controller
{
    

    private $cart = array();
    /**
     * 
     * $cart[$id] =  [
     *       "name" => $course->course_title,
     *       "quantity" => 1,
     *       "discount" => Discount in percentage : 0,
     *       "coupon => Coupon Code
     *      ];
     * 
     * 
     * 
     * 
     */

     public function __construct()
     {
        $this->middleware(function ($request, $next) {
            $this->cart = session()->get('cart')??null;
            return $next($request);
        });
         
     }
    
    public function add($id, Request $request)
    {
        $id = (int) $id;

        $course = Course::find($id);

        if (!$course) {
            abort(404);
        }

        $cart = session()->get('cart');

        // If it is already there

        if( $cart && array_key_exists($id, $cart) && $cart[$id]['type']=='course'){
            return json_encode(['status'=>200, 'message'=> 'Course is already added']);
        } else if($cart) {
           
            $cart[$id] =  [
                "name" => $course->course_title,
                "quantity" => 1,
                "discount" => $request->body ? $request->body['discount'] : 0,
                'type' => 'course'
            ];
            
            $this->setCart($cart);

            return json_encode(['status'=>200, 'message'=> 'Another Course is added to cart']);
        }

        // if cart is empty then this the first product
        if (!$cart) {

            $cart = [
                $id => [
                    "name"      => $course->course_title,
                    "quantity"  => 1,
                    "discount"  => $request->body ? $request->body['discount'] : 0,
                    'type' => 'course'
                ]
            ];

            session()->put('cart', $cart);
        }

        return json_encode(['status'=>200, 'message'=> 'Course is added to cart']);
    }

    public function addBook($id, Request $request)
    {
        $id = (int) $id;

        $book = ElibraryPost::find($id); 

        if (!$book) {
            abort(404);
        }

        $cart = session()->get('cart');

        // If it is already there

        if( $cart && array_key_exists($id, $cart) && $cart[$id]['type']=='book'){
            return json_encode(['status'=>200, 'message'=> 'Book is already added']);
        } else if($cart) {
           
            $cart[$id] =  [
                "name" => $book->book_title,
                "quantity" => 1,
                "discount" => $request->body ? $request->body['discount'] : 0,
                'type' => 'book'
            ];
            
            $this->setCart($cart);

            return json_encode(['status'=>200, 'message'=> 'This Book is added to cart']);
        }

        // if cart is empty then this the first product
        if (!$cart) {

            $cart = [
                $id => [
                    "name"      => $book->book_title,
                    "quantity"  => 1,
                    "discount"  => $request->body ? $request->body['discount'] : 0,
                    'type' => 'book'
                ]
            ];

            session()->put('cart', $cart);
        }

        return json_encode(['status'=>200, 'message'=> 'This Book is added to cart']);
    }

    public function cart()
    {
        return $this->jsonOutput($this->getCart());
    }


    public function itemCheck($id, Request $request, $type="course")
    {

        $id = (int) $id;

        $cart = session()->get('cart');

        if($cart && array_key_exists($id, $cart) && ($cart[$id]['type']==$type)){
            return json_encode(['status'=>200, 'has'=> 1]);
        } else {
            return json_encode(['status'=>200, 'has'=> 0]);
        }
    }

    public function applyCoupon(Request $request)
    {
         $request->validate([
            'code' => 'required',
            'course' => 'required'
        ]);

        $data = $request->all();

        $couponCheck = new CouponChecker;

        $ref =  $couponCheck->checkIfRef($data['code']);

        if($ref){
            // it is referrel coupon
            // with student, instructor and admin will get some amount
            
            return $this->applyRefferCoupon($data);
        } else {
            // normal, Just discount for this course

            return $this->applyDiscountCoupon($data);
 
        }

        
    }



    // Private Section

    private function getCart()
    {
        return ['count'=>count(session()->get('cart')??[]),'cart'=>session()->get('cart')??[]];
    }

    private function setCart($cart){
        return session()->put('cart', $cart);
    }

    private function jsonOutput($input){
        return json_encode($input);
    }

    private function applyDiscountCoupon($data){

        if(isset($this->cart[$data['course']])){
                
            if(isset($this->cart[$data['course']]['coupon'])){
                
                // if coupon code same
                if($this->cart[$data['course']]['coupon']==$data['code']){
                    return $this->jsonOutput(['status'=>404 , 'message'=> 'Coupon already used']);
                }
            } 
                $discount = new DiscountCoupon;
                $discount = $discount->getDiscount($data['code']);
                
                if(!$discount){
                    return $this->jsonOutput(['status'=>404 , 'message'=> 'Invalid Coupon']);
                }

                $this->cart[$data['course']]['coupon'] = $data['code'];
                $this->cart[$data['course']]['coupon_type'] = 'discount';
                $this->cart[$data['course']]['discount'] += $discount;

                $this->setCart($this->cart);

        }

        return $this->jsonOutput(['status'=>200, 'message'=>session()->get('cart')]);
    }

    private function applyRefferCoupon($data){

        if(isset($this->cart[$data['course']])){
                
            if(isset($this->cart[$data['course']]['coupon'])){
                
                // if coupon code same
                if($this->cart[$data['course']]['coupon']==$data['code']){
                    return $this->jsonOutput(['status'=>404 , 'message'=> 'Coupon already used']);
                }
            } 
                $discount = new RefferCoupon;
                $discount = $discount->getDiscount($data['code']);
                
                if(!$discount){
                    return $this->jsonOutput(['status'=>404 , 'message'=> 'Invalid Coupon']);
                }

                $this->cart[$data['course']]['coupon'] = $data['code'];
                $this->cart[$data['course']]['coupon_type'] = 'refferal';
                $this->cart[$data['course']]['discount'] += $discount;

                $this->setCart($this->cart);

        }

        return $this->jsonOutput(['status'=>200, 'message'=>session()->get('cart')]);
    }

}
