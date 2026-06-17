<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\Order\ProductController;
use App\Models\YIC_user;

Route::get('/', function () {
    return view('welcome');
});


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
});

//出品者グループ化
Route::prefix('user')
->name('user.')
->middleware(['role:user'])
->controller(OrderController::class)
->group(function() {
    Route::get('/create', 'create')->name('create');
    Route::post('/create', 'loginstore')->name('procss');

    Route::get('/dashboard', 'index')->name('dashboard');

    Route::post('/dashboard', 'storeProduct')->name('product.store');

    Route::get('/product/edit/{id}', 'edit')->name('edit');
    Route::put('/product/{id}', 'update')->name('update');
    Route::delete('/product/{id}', 'destroy')->name('destroy');

    Route::get('/product/{product_id}', [OrderController::class,'show'])->name('show');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

//買い手グループ化
Route::prefix('buyer')
->name('buyer.')
->middleware(['role:buyer'])
->group(function() {
    Route::get('/show/', [ProductController::class,'show'])->name('show');
    Route::get('/dashboard', [ProductController::class,'dashboard'])->name('dashboard');
    Route::get('/bids/{product_id}', [ProductController::class, 'bids'])->name('bids');
    Route::post('/bids/{product_id}', [ProductController::class, 'storeBid'])->name('bids.store');
});



