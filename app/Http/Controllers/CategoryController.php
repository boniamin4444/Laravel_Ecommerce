<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
    	$result['data'] = Category::all();
    	return view('admin.category', $result);
    }

    public function manage_category(Request $request, $id='')
    {
    	if($id > 0)
    	{
    		$arr = Category::where(['id'=>$id])->get();
    		$result['category_name'] = $arr['0']->category_name;
    		$result['id'] = $arr['0']->id;
    	}
    	else
    	{
    		$result['category_name'] = '';
    		$result['id'] = 0;
    	}
    	return view('admin/manage_category', $result);
    }

    public function manage_category_process(Request $request)
    {
    	$request->validate([

    		'category_name'=>'required|unique:categories',
    	]);

    	if($request->post('id') > 0)
    	{
    		$model = Category::find($request->post('id'));
    		$msg = "Category Updated";
    	}

    	else
    	{
    		$model = new Category();
    		$msg = "category Inserted";
    	}

    	$model->category_name = $request->post('category_name');
    	$model->save();
    	$request->session()->flash('message', $msg);
    	return redirect('admin/category');
    }

    public function delete(Request $request, $id)
    {
    	$model = Category::find($id);
    	$model->delete();
    	$request->session()->flash('message', 'Category Deleted');
    	return redirect('admin/category');
    }
}
