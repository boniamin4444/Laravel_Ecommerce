<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;
use App\Models\Inventory;

class inventoryController extends Controller
{
    public function showStockForm()
    {
    	$categories = Category::all();
    	return view('admin.inventory.stock', compact('categories'));
    }

    public function getProductsByCategory($categoryId)
    {
    	$products = Product::where('category_id',$categoryId)->get();
    	return response()->json($products);
    }

    public function storeStock(Request $request)
    {
    	$request->validate([

    		'product_id' => 'required',
    		'stock_in' => 'required|integer',
    		'vat' => 'required|numeric',
    	]);

    	$product = Product::find($request->product_id);
    	$calculatedVat = ($product->price * $request->vat) / 100;

    	$product->increment('stock',$request->stock_in);

    	Inventory::create([

    		'product_id' => $request->product_id,
    		'stock_in' => $request->stock_in,
    		'vat' => $request->vat,
    		'calculated_vat' => $calculatedVat,
    	]);

    	return redirect()->back()->with('success','Stock Updated Successfully');

    }
}
