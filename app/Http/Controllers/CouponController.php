<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function index()
    {
    	$coupons = Coupon::all();

    	foreach($coupons as $coupon)
    	{
    		if($coupon->expire_date < Carbon::now())
    		{
    			$coupon->status = "inactive";
    			$coupon->save();
    		}
    	}

    	return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
    	return view('admin.coupons.create');
    }

    public function store(Request $request)
    {	
    	$request->validate([

    		'coupon_code'=>'required|unique:coupons|max:255',
    		'expire_date'=>'required|date',
    		'values'=>'required|numeric|min:1|max:100',
    		'status'=>'required'
    	]);

    	Coupon::create([

    		'coupon_code' =>$request->coupon_code,
    		'expire_date'=>$request->expire_date,
    		'value'=>$request->values,
    		'status'=>$request->status
    	]);

    	return redirect()->route('coupons.index')->with('success','Coupon Created Successfully');
    }

    public function edit(Coupon $coupon)
    {
    	return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
    	$request->validate([

    		'coupon_code'=>'required|max:255',
    		'expire_date'=>'required|date',
    		'values'=>'required|numeric|min:1|max:100',
    		'status'=>'required'
    	]);

    	$coupon->update($request->all());

    	return redirect()->route('coupons.index')->with('success','Coupon Updated');
    }

    public function destroy(Coupon $coupon)
    {
    	$coupon->delete();
    	return redirect()->route('coupons.index')->with('success','Coupon Deleted');
    }

    public function checkExpireCoupons()
    {
    	$coupons = Coupon::where('expire_date', '<', now())->update(['status'=>'inactive']);
    }
}
