<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\basicController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\frontController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\SupplierController;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [frontController::class, "index"]);

Route::get('login', function () {
    return redirect('/products');
})->name('login');

Route::get('register', function () {
    return redirect('/products');
})->name('register');

//POST routes (handling form submission)

Route::post('login',[AuthController::class,'login'])->name('login.post');
Route::post('register',[AuthController::class,'register'])->name('register.post');
Route::get('logout',[AuthController::class,'logout'])->name('logout');

//Email Verify Route

Route::get('email/verify/{id}/{hash}', [AuthController::class,'verifyEmail'])->name('verification.verify');

//Fetching filtered products routes according t6o categories and prices
Route::get('/products', [frontController::class, 'index'])->name('products.index');

Route::post('/products/filter', [frontController::class, 'filterProducts'])->name('products.filter');

Route::middleware(['custom.auth'])->group(function(){

	

	//Cart Routes

	Route::get('/cart',[CartController::class, 'viewCart'])->name('cart.view');

	Route::post('/cart/add',[CartController::class,'addToCart'])->name('cart.add');

	Route::delete('/cart/remove',[CartController::class, 'removeFromCart'])->name('cart.remove');

	Route::post('/cart/clear',[CartController::class, 'clearCart'])->name('cart.clear');

	//Coupon Routes

	Route::post('/cart/apply-coupon',[CartController::class,'applyCoupon'])->name('cart.applyCoupon');
	Route::post('cart/place-order', [CartController::class,'placeOrder'])->name('cart.placeOrder');
});

//Admin Routes

	Route::get('admin',[AdminController::class, 'index']);
	Route::post('admin/auth',[AdminController::class, 'auth'])->name('admin.auth');
	
	// Route::get('/admin/updatepass',[AdminController::class, 'updatePassword']);

	Route::group(['middleware'=>'admin_auth'], function(){

		Route::get('admin/dashboard',[AdminController::class, 'dashboard']);
		Route::get('admin/category',[CategoryController::class, 'index']);
		Route::get('admin/category/manage_category',[CategoryController::class, 'manage_category']);
		Route::get('admin/category/manage_category/{id}',[CategoryController::class, 'manage_category']);

		Route::post('admin/category/manage_category_process',[CategoryController::class, 'manage_category_process'])->name('category.manage_category_process');

		Route::get('admin/category/delete/{id}', [CategoryController::class, 'delete']);

	//Product Routes
	Route::resource('products', ProductController::class);

	Route::get('/products/{id}/edit',[ProductController::class,'edit'])->name('products.edit');

	Route::put('/products/{id}',[ProductController::class,'update'])->name('products.update');	



	Route::get('admin/showspplier',[SupplierController::class, 'index']);
Route::get('admin/supplier/manage_supplier',[SupplierController::class, 'manage_supplier']);
Route::get('admin/supplier/manage_supplier/{id}',[SupplierController::class, 'manage_supplier']);
Route::post('admin/supplier/manage_supplier_process',[SupplierController::class, 'manage_supplier_process'])
->name('supplier.manage_supplier_process');
Route::get('admin/supplier/delete/{id}', [SupplierController::class, 'delete']);


	});

	//Coupon Routes

	Route::resource('coupons', CouponController::class);
	
	//Product Notification Routes

	Route::get('/notifications/mark-as-read', function(){

		auth()->user()->unreadNotifications->markAsRead();
		return redirect()->back();
	})->name('notifications.markAsRead');

	Route::get('/product-details/{id}' , function($id){

		$product = Product::findOrFail($id);

		auth()->user()->unreadNotifications->where('data.product_id', $id)->markAsRead();
		return view('products.product-details', compact('product'));
	})->name('product.details');

	//Order Notification Route

	Route::get('/admin/pending-orders-count',[AdminController::class,'getPendingOrdersCount'])->name('admin.orders.index');

	Route::get('/admin/pending-orders',[AdminController::class,'getPendingOrders'])->name('admin.showOrder');
	
	Route::post('/admin/statusUpdate/change/{order_id}',[AdminController::class,'statusUpdate'])->name('admin.showOrder.status.change');
	
	//Logout Route
	Route::get('admin/logout', function(){

		session()->forget('ADMIN_LOGIN');
		session()->forget('ADMIN_ID');
		session()->flash('error','Logout Successfully');
		return redirect('admin');
	});








