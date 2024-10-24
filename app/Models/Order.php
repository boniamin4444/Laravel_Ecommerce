<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','user_id','order_amount','total_discounted_price','quantity','status'];

    protected $primaryKey = 'order_id';

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
    
}
