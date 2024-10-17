<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
