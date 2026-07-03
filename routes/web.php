<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// あなたが作成したコントローラーの読み込み
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\Order\ProductController;
use App\Models\YIC_user;



// トップページにアクセスしたら商品一覧へリダイレクト
Route::get('/', function () {
    return redirect()->route('buyer.product_list');
});

// 買い手用の商品一覧・詳細画面（ログイン不要）
Route::prefix('buyer')->name('buyer.')->group(function() {
    Route::get('/product_list', [ProductController::class, 'index'])->name('product_list');
    Route::get('/show/{product_id}', [ProductController::class, 'show'])->name('show');
});


Route::prefix('user/login')->controller(AuthController::class)->name('user.login.')->group(function() {
    Route::get('/', 'showUserLogin')->name('index');
    Route::post('/', 'loginProcess')->name('process');
});

// route('login') -> 独自ログイン画面
Route::get('/login', [AuthController::class, 'showUserLogin'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');

// route('register') -> 独自新規登録画面
Route::get('/register', [AuthController::class, 'create'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');

// 旧パス（/user/register）との互換性のため残す場合
Route::get('/user/register', [AuthController::class, 'create'])->name('user.register');
Route::post('/user/register', [AuthController::class, 'store'])->name('user.register.store');

// パスワード再発行（独自実装）
Route::prefix('user/reissue')
    ->controller(AuthController::class)
    ->name('user.reissue.')
    ->group(function() {
        Route::get('/', 'showReissue')->name('index');
        Route::post('/', 'processReissue')->name('process');
    });

Route::prefix('admin/system/login')->controller(AuthController::class)->name('admin.system.login.')->group(function() {
    Route::get('/', 'showAdminLogin')->name('index');
    Route::post('/', 'loginProcess')->name('process');
});

// ログアウト（独自実装、route('logout')として統一）
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('user.logout'); // 旧Bladeとの互換性用


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 買い手グループ化
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
            Route::patch('/notification/hide/{transaction_id}', [ProductController::class, 'hideNotification'])->name('notification.hide');
        });

    // 出品者グループ化
    Route::prefix('user')
        ->name('user.')
        ->middleware(['role:user'])
        ->controller(OrderController::class)
        ->group(function() {
            Route::get('/dashboard', 'dashboard')->name('dashboard');
            Route::post('/dashboard', 'storeProduct')->name('product.store');
            Route::get('/product/edit/{id}', 'edit')->name('edit');
            Route::put('/product/{id}', 'update')->name('update');
            Route::delete('/product/{id}', 'destroy')->name('destroy');
            Route::get('/product/{product_id}', 'show')->name('show');
            Route::get('/notification/{transaction_id}', 'shownotifiction')->name('notification.show');
            Route::post('/notification/{transaction_id}', 'processNotification')->name('notification.process');
        });

    // ショップ管理者グループ化
    Route::prefix('admin/shop')
        ->name('admin.shop.')
        ->middleware(['role:shop'])
        ->controller(ShopController::class)
        ->group(function() {
            Route::get('/dashboard', 'index')->name('dashboard');
            Route::get('/edit/{product_id}', 'edit')->name('edit');
            Route::put('/{product_id}', 'update')->name('update');
            Route::delete('{product_id}', 'destroy')->name('destroy');
            Route::get('/shipping/{transaction_id}', 'showshipping')->name('shipping.show');
            Route::post('/shipping/{transaction_id}', 'processShipping')->name('shipping.process');
            Route::get('/transfer/{transaction_id}', 'showtransfer')->name('transfer.show');
            Route::post('/transfer/{transaction_id}', 'processTransfer')->name('transfer.process');
            Route::delete('/bid/{bid_id}', 'destroyBid')->name('bid.destroy');
            Route::patch('/bid/{bid_id}', 'updateBid')->name('bid.update');
        });

    // システム管理者グループ化
    Route::prefix('admin/system')
        ->name('admin.system.')
        ->middleware(['role:system'])
        ->controller(SystemController::class)
        ->group(function() {
            Route::get('/dashboard', 'index')->name('dashboard');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('{id}', 'destroy')->name('destroy');
        });
});


require __DIR__.'/auth.php';
