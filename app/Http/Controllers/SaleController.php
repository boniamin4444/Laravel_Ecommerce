<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Inventory;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    // Show the sale creation form
    public function create()
    {
        $categories = Category::all();
        $users = User::all();

        return view('sales.create', compact('categories', 'users'));
    }

    // Fetch products based on the selected category
    public function fetchProducts(Request $request)
    {
        $products = Product::where('category_id', $request->category_id)->get();
        return response()->json($products);
    }

    // Fetch inventory data (VAT) and product price based on the selected product
    public function fetchInventory($id)
    {
        $inventory = Inventory::where('product_id', $id)->first();
        $product = Product::findOrFail($id);  // Fetch price from product table

        return response()->json([
            'calculated_vat' => $inventory ? $inventory->calculated_vat : 0,
            'price' => $product->price,  // Return price from product table
        ]);
    }

    // Store the sale record in the database
    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Fetch the price from the product table
        $product = Product::findOrFail($request->product_id);
        $price = $product->price;


        $user_id = User::where('id', $request->user_id)->first();
        // Fetch the calculated VAT from the inventory table
        $inventory = Inventory::where('product_id', $request->product_id)->first();
        $calculated_vat = $inventory ? $inventory->calculated_vat : 0;

        // Create a new sale record
        Sale::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $price * $request->quantity, // Price from product table multiplied by quantity
            'calculated_vat' => $calculated_vat * $request->quantity, // VAT from inventory
        ]);

        return redirect()->back()->with('success', 'Sale recorded successfully!');
    }
}
