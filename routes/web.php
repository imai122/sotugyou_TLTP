<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\Order\ProductController;
use App\Models\YIC_user;

Route::get('/', function () {
    return redirect()->route('buyer.product_list');
});

Route::get('/logout', [AuthController::class, 'logout'])
    ->name('user.logout') // Bladeファイルで使っている名前に合わせる
    ->middleware('auth');


Route::get('/login', function () {
    
    return redirect()->route('user.login.index'); 
})->name('login');

Route::prefix('admin/system/login')
    ->controller(AuthController::class)
    ->name('admin.system.login.')
    ->group(function() {
        Route::get('/', 'showAdminLogin')->name('index');
        Route::post('/', 'loginProcess')->name('process'); 
    });


Route::prefix('user')
->name('user.')
->controller(OrderController::class)
->group(function() {
    Route::get('/create', 'create')->name('create');
    Route::post('/create', 'loginstore')->name('procss');
});




Route::prefix('user/login')
    ->controller(AuthController::class) 
    ->name('user.login.')
    ->group(function() {
        Route::get('/', 'showUserLogin')->name('index');
        Route::post('/', 'loginProcess')->name('process'); 
    });


//システム管理者グループ化
Route::prefix('admin/system')
->name('admin.system.')
->middleware(['role:system'])
->controller(SystemController::class)
->group(function() {
    Route::get('/dashboard', 'index')->name('dashboard');
    Route::get('/edit/{id}', 'edit')
    ->name('edit');
    Route::put('/{id}', 'update')
    ->name('update');

    Route::delete('{id}', 'destroy')
    ->name('destroy');
});

//ショップ管理者グループ化
Route::prefix('admin/shop')
->name('admin.shop.')
->middleware(['auth', 'role:shop'])
->controller(ShopController::class)
->group(function() {
    Route::get('/dashboard', 'index')->name('dashboard');
    Route::get('/edit/{product_id}', 'edit')->name('edit');
    Route::put('/{product_id}', 'update')->name('update');
    Route::delete('{product_id}', 'destroy')->name('destroy');
    Route::get('/shipping/{transaction_id}', [ShopController::class, 'showshipping'])->name('shipping.show');
    Route::post('/shipping/{transaction_id}', [ShopController::class, 'processShipping'])->name('shipping.process');
    Route::get('/transfer/{transaction_id}', [ShopController::class, 'showtransfer'])->name('transfer.show');
    Route::post('/transfer/{transaction_id}', [ShopController::class, 'processTransfer'])->name('transfer.process');
    Route::delete('/admin/shop/bid/{bid_id}', [App\Http\Controllers\Admin\ShopController::class, 'destroyBid'])->name('admin.shop.bid.destroy');
Route::patch('/admin/shop/bid/{bid_id}', [App\Http\Controllers\Admin\ShopController::class, 'updateBid'])->name('admin.shop.bid.update');
});

//出品者グループ化
Route::prefix('user')
->name('user.')
->middleware(['role:user'])
->controller(OrderController::class)
->group(function() {
    // Route::get('/create', 'create')->name('create');
    // Route::post('/create', 'loginstore')->name('procss');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/dashboard', 'storeProduct')->name('product.store');
    Route::get('/product/edit/{id}', 'edit')->name('edit');
    Route::put('/product/{id}', 'update')->name('update');
    Route::delete('/product/{id}', 'destroy')->name('destroy');
    Route::get('/product/{product_id}', [OrderController::class,'show'])->name('show');
    // Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/notification/{transaction_id}', [OrderController::class, 'shownotifiction'])->name('notification.show');
    Route::post('/notification/{transaction_id}', [OrderController::class, 'processNotification'])->name('notification.process');
});

Route::prefix('buyer')
->name('buyer.')
->group(function() {
    Route::get('/product_list', [ProductController::class, 'index'])->name('product_list');
    Route::get('/show/{product_id}', [ProductController::class, 'show'])->name('show');
});

//買い手グループ化
Route::prefix('buyer')
->name('buyer.')
->middleware(['role:buyer'])
->group(function() {
    Route::get('/dashboard', [ProductController::class,'dashboard'])->name('dashboard');
    Route::get('/bids/{product_id}', [ProductController::class, 'bids'])->name('bids');
    Route::post('/bids/{product_id}', [ProductController::class, 'storeBid'])->name('bids.store');
    Route::get('/deposit/{transaction_id}', [ProductController::class, 'showdeposit'])->name('deposit.show');
    Route::post('/deposit/{transaction_id}', [ProductController::class, 'processDeposit'])->name('deposit.process');
    Route::get('/check/{transaction_id}',[ProductController::class, 'showcheck'])->name('check.show');
    Route::post('/check/{transaction_id}', [ProductController::class, 'processCheck'])->name('check.process');
});



