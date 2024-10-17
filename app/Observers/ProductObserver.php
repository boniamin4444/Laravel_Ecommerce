<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\User;
use App\Mail\NewProductNotification;
use Illuminate\Support\Facades\Mail;

class ProductObserver
{
    
    public function created(Product $product)
    {
        $users = User::all();

        foreach($users as $user)
        {
            //Send email to each user using queue
            Mail::to($user->email)->queue(new NewProductNotification($product));
        }
    }

    
    public function updated(Product $product)
    {
        
    }

    
    public function deleted(Product $product)
    {
       
    }

    
    public function restored(Product $product)
    {
       
    }

   
    public function forceDeleted(Product $product)
    {
        
    }
}
