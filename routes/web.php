<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    BasicController,
    ProductController,
    AuthController,
    FrontController,
    CartController,
    AdminController,
    CategoryController,
    CouponController,
    SupplierController,
    InventoryController,
    SaleController
};
use App\Models\Product;

// Public Routes
Route::get('/', [FrontController::class, "index"]);

// Login and Registration Redirects
Route::get('login', fn() => redirect('/products'))->name('login');
Route::get('register', fn() => redirect('/products'))->name('register');

// POST routes (handling form submission)
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('register', [AuthController::class, 'register'])->name('register.post');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Email Verification Route
Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

// Fetching filtered products routes according to categories and prices
Route::get('/products', [FrontController::class, 'index'])->name('products.index');
Route::post('/products/filter', [FrontController::class, 'filterProducts'])->name('products.filter');

// Authenticated User Routes
Route::middleware(['custom.auth'])->group(function() {
    // Cart Routes
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');

    // Coupon Routes
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
    Route::post('/cart/place-order', [CartController::class, 'placeOrder'])->name('cart.placeOrder');
});

// Admin Routes
Route::get('admin', [AdminController::class, 'index']);
Route::post('admin/auth', [AdminController::class, 'auth'])->name('admin.auth');

// Admin Authenticated Routes
Route::middleware(['admin_auth'])->group(function() {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
    
    // Category Routes
    Route::get('admin/category', [CategoryController::class, 'index']);
    Route::get('admin/category/manage_category', [CategoryController::class, 'manage_category']);
    Route::get('admin/category/manage_category/{id}', [CategoryController::class, 'manage_category']);
    Route::post('admin/category/manage_category_process', [CategoryController::class, 'manage_category_process'])->name('category.manage_category_process');
    Route::get('admin/category/delete/{id}', [CategoryController::class, 'delete']);

    // Product Routes
    Route::resource('products', ProductController::class);
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');

    // Supplier Routes
    Route::get('admin/showspplier', [SupplierController::class, 'index']);
    Route::get('admin/supplier/manage_supplier', [SupplierController::class, 'manage_supplier']);
    Route::get('admin/supplier/manage_supplier/{id}', [SupplierController::class, 'manage_supplier']);
    Route::post('admin/supplier/manage_supplier_process', [SupplierController::class, 'manage_supplier_process'])->name('supplier.manage_supplier_process');
    Route::get('admin/supplier/delete/{id}', [SupplierController::class, 'delete']);

    // Inventory Routes
    Route::get('/inventory/stock', [InventoryController::class, 'showStockForm'])->name('inventory.store');
    Route::get('/get-products/{categoryId}', [InventoryController::class, 'getProductsByCategory']);
    Route::post('/inventory/stock', [InventoryController::class, 'storeStock'])->name('inventory.store');

    // Sales Routes
    Route::get('/admin/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/admin/sales/store', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/admin/sales/fetch-products', [SaleController::class, 'fetchProducts'])->name('sales.fetchProducts');
    Route::get('/admin/inventories/{id}', [SaleController::class, 'fetchInventory'])->name('sales.fetchInventory');

    // Order Notification Routes
    Route::get('/admin/pending-orders-count', [AdminController::class, 'getPendingOrdersCount'])->name('admin.orders.index');
    Route::get('/admin/pending-orders', [AdminController::class, 'getPendingOrders'])->name('admin.showOrder');
    Route::post('/admin/statusUpdate/change/{order_id}', [AdminController::class, 'statusUpdate'])->name('admin.showOrder.status.change');

    // Logout Route
    Route::get('admin/logout', function() {
        session()->forget('ADMIN_LOGIN');
        session()->forget('ADMIN_ID');
        session()->flash('error', 'Logout Successfully');
        return redirect('admin');
    });
});

// Product Notification Routes
Route::get('/notifications/mark-as-read', function() {
    auth()->user()->unreadNotifications->markAsRead();
    return redirect()->back();
})->name('notifications.markAsRead');

// Product Details Route
Route::get('/product-details/{id}', function($id) {
    $product = Product::findOrFail($id);
    auth()->user()->unreadNotifications->where('data.product_id', $id)->markAsRead();
    return view('products.product-details', compact('product'));
})->name('product.details');
