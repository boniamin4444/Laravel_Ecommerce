<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; 
use App\Notifications\OrderStatusChangeNotification;
use Illuminate\Support\Facades\Hash;
use App\Mail\StatusChangeMail;

class AdminController extends Controller
{
    // Show login page
    public function index()
    {
        return view('admin.login');
    }

    // Get pending orders count
    public function getPendingOrdersCount()
    {
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        return response()->json(['pendingOrdersCount' => $pendingOrdersCount]);
    }

    // Get pending orders
    public function getPendingOrders()
    {
        $pendingOrders = Order::where('status', 'pending')->paginate(10);
        return view('admin.showOrder', compact('pendingOrders'));
    }

    // Update order status
    public function statusUpdate(Request $request, $order_id)
    {
        $order = Order::find($order_id);

        if ($order) {
            $order->status = $request->input('status');
            $order->save();

            if ($order->status === 'completed') {
                // Update product stock
                $product = Product::find($order->product_id);
                $product->stock -= $order->quantity;
                $product->save();

                // Send email to user
                $user = User::find($order->user_id);
                Mail::to($user->email)->send(new StatusChangeMail($order));
            }

            return redirect()->route('admin.showOrder', ['order' => $order_id])
                             ->with('success', 'Status updated successfully');
        } else {
            return redirect()->back()->with('error', 'Order not found');
        }
    }

    // Admin authentication logic
    public function auth(Request $request)
    {
        $email = $request->post('email');
        $password = $request->post('password');

        $result = Admin::where(['email' => $email])->first();

        if ($result) {
            if (Hash::check($password, $result->password)) {
                $request->session()->put('ADMIN_LOGIN', true);
                $request->session()->put('ADMIN_ID', $result->id);
                return redirect('admin/dashboard');
            } else {
                $request->session()->flash('error', 'Please enter a valid password');
                return redirect('admin');
            }
        } else {
            $request->session()->flash('error', 'Please enter valid login details');
            return redirect('admin');
        }
    }

    // Show dashboard
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // Show the report page
    public function showReport()
    {
        return view('admin.orders.report');
    }

    // Fetch the report data
    public function fetchReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $orders = Order::with(['product', 'user'])
                        ->where('status', 'completed')
                        ->when($startDate && $endDate, function($query) use ($startDate, $endDate) {
                            return $query->whereBetween('created_at', [$startDate, $endDate]);
                        })
                        ->get(); // Moved the get() outside of when()

        return response()->json($orders);
    }
}
