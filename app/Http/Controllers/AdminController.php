<?php


namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Add this line
use App\Notifications\OrderStatusChangeNotification;
use Illuminate\Support\Facades\Hash;
use App\Mail\StatusChangeMail;



class AdminController extends Controller
{
   
    public function index()
    {
      return view('admin.login');
    }

    public function getPendingOrdersCount()
    {
      $pendingOrdersCount = Order::where('status','pending')->count();

      return response()->json(['pendingOrdersCount'=>$pendingOrdersCount]);
    }
   

    public function getPendingOrders()
  {
    // Get all pending orders
    $pendingOrders = Order::where('status', 'pending')->paginate(10);

    // Return a view with the pending orders
    return view('admin.showOrder', compact('pendingOrders'));
  }

  public function statusUpdate(Request $request, $order_id)
{
    // Find the order by its id
    $order = Order::find($order_id);

    // Check if the order exists
    if ($order) {
        // Update the status of the order from the request input
        $order->status = $request->input('status');
        $order->save(); // Save the updated order

        if ($order->status === 'completed') {
          $product = Product::find($order->product_id);
          $product->stock -= $order->quantity;
          $product->save();
           }

          if($order->status === 'completed')
          {
             $user = User::find($order->user_id);
             Mail::to($user->email)->send(new StatusChangeMail($order));
          }

        // Redirect back to the order page with a success message
        return redirect()->route('admin.showOrder', ['order' => $order_id])
                         ->with('success', 'Status updated successfully');
    } else {
        // Return with an error message if order not found
        return redirect()->back()->with('error', 'Order not found');
    }

}

   
   
   
   
   
   
      /*public function updatePassword()
    {
        $r = Admin::find(1);
        $r->password = Hash::make('12345678');
        $r->save();
    }*/

    public function auth(Request $request)
    {
       /* return $request->post();*/

       $email = $request->post('email');
       $password = $request->post('password');
      
      /* $result = Admin::where(['email'=>$email, 'password'=>$password])->get();*/

       /*echo '<pre>';
       print_r($result);
       echo '</pre>';*/

       $result= Admin::where(['email'=>$email])->first();

       if($result)
       {
        
        if(Hash::check($request->post('password'),$result->password))
        {
            $request->session()->put('ADMIN_LOGIN', true);
            $request->session()->put('ADMIN_ID', $result->id);
            return redirect('admin/dashboard');
        }
        else
        {
        $request->session()->flash('error','Please enter valid password');
        return redirect('admin');
        }
       }
       else
       {
        $request->session()->flash('error','Please enter valid login details');
        return redirect('admin');
       }       
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

   
}
