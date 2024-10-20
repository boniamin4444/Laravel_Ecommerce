<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Admin;
use App\Models\User;
use Carbon\Carbon;
use App\Notifications\pendingOrderNotification;

class CartController extends Controller
{
    public function viewCart()
    {
    	$cart = session()->get('cart',[]);
    	return view('cart.cart', compact('cart'));
    }

    public function addToCart(Request $request)
    {
    	$productId = $request->input('product_id');
    	$product = Product::find($productId);

        if(!$product)
        {
            return response()->json(['error','Invalid product'], 404);
        }

    	$cart = session()->get('cart',[]);

    	if(isset($cart[$productId]))
    	{
    		$cart[$productId]['quantity']++;
    	}

    	else
    	{
    		$cart[$productId]  = [

    			"product_id" =>$product->id,
                "name" => $product->product_name,
    			"quantity"=> 1,
    			"price" => $product->price,
                "image" => $product->image
    		];
    	}


    	session()->put('cart',$cart);

    	return response()->json([
            'success' => true,
            'cartCount' => count($cart),
            'message'=>'Product added to the cart' 
        ]);
    }

    public function removeFromCart(Request $request)
    {
    	$cart = session()->get('cart');

    	if(isset($cart[$request->product_id]))
    	{
    		unset($cart[$request->product_id]);
    		session()->put('cart', $cart);
    		return response()->json(['status'=>'Product removed successfully']);
    	}

    	return response()->json(['status'=>'Product not found in the cart'], 404);
    }

    public function clearCart()
    {
    	session()->forget('cart');
    	return redirect()->back()->with('success','Cart cleared successfully');
    }

    //Coupon Apply Functions

    public function applyCoupon(Request $request)
    {
        //Validate Control

        $request->validate([

            'coupon_code' =>'required|string',
        ]);

        //Fetch the coupon info from database

        $coupon = Coupon::where('coupon_code', $request->coupon_code)
                            ->where('status','active')
                            ->where('expire_date', '>=', Carbon::now())
                            ->first();
        if(!$coupon)
        {
            return back()->with('error','Invalid or Expired Coupon');
        }

        //Calculate discount

        $cartItems = session()->get('cart', []);
        $subtotal = array_reduce($cartItems, function($sum, $item){

            return $sum + ($item['price'] * $item['quantity']);
        },0);

        $discount = ($subtotal * $coupon->value) / 100;

        session()->put('discount', $discount);
        session()->put('coupon_code',$coupon->coupon_code);

        return back()->with('success','Coupon applied successfully');
    }

    function placeOrder(Request $request)
    {
        $user = auth()->user();
        $cart = session('cart');

        if(!$cart || count($cart) == 0)
        {
            return redirect()->back()->with('error','Your Cart is Empty');
        }       


        $couponCode = session('coupon_code', null);
        $discount = session('discount', 0);

        foreach($cart as $cartItem)
        {
            $product = Product::find($cartItem['product_id']);

            if(!$product)
            {
                return redirect()->back()->with('error','One or more products in your cart are invalid');
            }

            if($couponCode)
            {
            $coupon = Coupon::where('coupon_code',$couponCode)->where('status','active')->first();

              if($coupon)
              {
                $discount = $coupon->value / 100;
              }


              else
              {
                return redirect()->back()->with('error','Invalid or expired coupon code.');
              }
            }

                $orderAmount = $product->price * $cartItem['quantity'];
            
                $discountAmount =  $orderAmount * $discount;        
                $discountedPrice = $orderAmount - $discountAmount;
           

            $order = Order::create([
                'product_id'=>$product->id,
                'user_id'=>$user->id,
                'order_amount'=>$cartItem['quantity'] * $product->price,
                'total_discounted_price' =>$discountedPrice,
                'quantity'=>$cartItem['quantity'],
            ]);
            

            /* if ($order->status === 'completed') { */
            /*     $product->stock -= $cartItem['quantity']; */
            /*     $product->save(); */
            /* } */
    


            //for admin notifications

            $admin = Admin::where('role','admin')->first();
            $admin->notify(new pendingOrderNotification($order));

            
        }

        session()->forget('cart');
        session()->forget('discount');
        session()->forget('coupon_code');

        return back()->with('success','Order Placed Successfully');
    }
}
