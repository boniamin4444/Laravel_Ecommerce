<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;


class frontController extends Controller
{
    
    // Display all products and categories
    public function index()
    {
        $categories = Category::all();
        $products = Product::paginate(5); // Initially load all active products

        return view('welcome', compact('categories', 'products'));
    }

    // Filter products by selected categories using AJAX
    public function filterProducts(Request $request)
    {
        $products = Product::query();

        // If categories are selected, filter products by the selected categories

        if ($request->has('categories') && !empty($request->categories)) 
        {
            

            $products = $products->whereIn('category_id', $request->categories);
        } 
        
        //Filter by selecting price

        if ($request->has('prices') && !empty($request->prices)) 
        {
           $priceRanges = $request->prices;

            $products = $products->where(function($query) use ($priceRanges)
            {
                foreach($priceRanges as $range)
                {
                    [$min, $max] = explode('-',$range);
                    $query->orWhereBetween('price',[(int)$min, (int)$max]);
                }
            });
           
        }

        $products = $products->paginate(5);

        $html = view('products.filtered',compact('products'))->render();

        $pagination = $products->links()->render();

        return response()->json(['html'=>$html, 'pagination'=>$pagination]);
            
    }
  }