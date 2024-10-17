<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\View;

class basicController extends Controller
{
    public function index()
    {
    	$posts = 'MyPosts';
    	return view('testView')->with('posts',$posts)->with('datePublished','25-08-2024');
    }

    public function post($id)
    
    {

    	return view('testView',['id'=>$id]);
    }

   
}
