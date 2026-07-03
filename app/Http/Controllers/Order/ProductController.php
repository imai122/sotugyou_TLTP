<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Request\CreateRequest;
use App\Http\Requests\BidRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\YIC_user;
use App\Models\Product;
use App\Models\Bid;
use App\Models\Transaction;


class ProductController extends Controller
{
    //

     public function  loginstore(CreateRequest $request): RedirectResponse//ログイン処理
     {
        $data = $request->validated();

       

        $yic_users = YIC_user::create([
        'name'          => $data['name'],
        'address'       => $data['address'],
        'postal_code'   => $data['postal_code'],
        'phone_number'  => $data['phone_number'],
        'email'         => $data['email'],
        'bank_account'  => $data['bank_account'],
        'user_id' => $data['user_id'],
        'password'      => Hash::make($data['password']),
        'role' => $data['role'],
        ]);
    return redirect()->route('user.login.index');

     }


    public function index(): View//閲覧
    {
        Product::updatedExpiredStatus();
        $products = Product::whereHas('yic_users', function ($query) { 
        $query->where('role', 3); // 出品者のみ
    })

    ->where('end_date', '>', now()) 
        ->get();

        return view('buyer.product_list', compact('products'));
    }

    public function show($product_id): View
{
    $product = \App\Models\Product::with('categories')->findOrFail($product_id);
    
    
    return view('buyer.show', compact('product'));
}

     public function create(): View
    {
       $data = $this->getSharedData();


        return view('buyer.dashboard', $data);

    }


   public function bids($product_id): View
{
    // 入札する商品の情報をデータベースから取得
    $product = Product::findOrFail($product_id);

    // 商品情報を view に渡す
    return view('buyer.bids', compact('product'));
}

public function storeBid(BidRequest $request, $product_id)
{
    $data = $request->validated();
    $yic_users = YIC_user::find(auth()->id());//現在ログインしているユーザ取得

    $alreadyBid = Bid::where('product_id', $product_id)//商品ID取得
                     ->where('bidder_id', $yic_users->user_id)//ログインID取得
                     ->exists(); // あれば true、なければ false

    // もしすでに履歴があったら、入札させずにエラーを返してダッシュボードに戻す
    if ($alreadyBid) {
        return redirect()->route('buyer.dashboard')
        ->with('error', 'この商品にはすでに入札しています。同じ商品への入札は1回までです。')
        ->with('tab', 'history');
    }

    Bid::create([
        'product_id' => $product_id,
        'bidder_id'  => $yic_users->user_id, 
        'bid_amount' => $data['bid_amount'],
        'bid_at'     => now(), // 現在の時刻をセット
    ]);

    return redirect()->route('buyer.dashboard')
    ->with('success', '入札が完了しました！')
    ->with('tab', 'history');
}

      
    public function dashboard(Request $request)
    {
        Product::updateExpiredStatus();
        $userId = auth()->id();
        $loginUserId = auth()->user()->user_id;
        $bidsQuery = Bid::with('products')->where('bidder_id', $userId);

        if ($request->filled('history')) {
            // リレーション先の「商品名」で絞り込み
            $bidsQuery->whereHas('products', function($q) use ($request) {
                $q->where('product_name', 'LIKE', '%' . $request->history . '%');
            });
        }
        
        $my_bids = $bidsQuery->orderBy('bid_at', 'desc')->get();


         $otherProductsQuery = Product::with('yic_users') 
         ->where('seller_id', '!=', auth()->id())
         ->whereHas('yic_users', function ($query) { 
         $query->where('role', 3); // 出品者のみ
         })
         ->where('end_date', '>', now());

        if (request()->filled('name')) {
        $otherProductsQuery->where('product_name', 'LIKE', '%' . request('name') . '%');
    
    }

    $other_products = $otherProductsQuery->orderBy('created_at', 'desc')->get();

      
    $won_transactions = Transaction::with('products')
    ->where('buyer_id', auth()->id())
        // ->where('buyer_id', auth()->user()->user_id)
        // ->where('status',1)
    ->orderBy('won_at', 'desc')
    ->get();   

    $won_product_ids = $won_transactions->pluck('product_id')->toArray();
    $unread_count = $won_transactions->whereIn('status', [1, 3])->count();
    $purchase_count = auth()->user()->purchase_count;

    $activeTab = $request->input('tab', 'product-show');
    
        return view('buyer.dashboard', 
        compact(
            'other_products', 'my_bids', 'won_transactions', 'unread_count', 'purchase_count', 'won_product_ids', 'activeTab'));
    }

    public function showdeposit($transaction_id)
    {
          $transaction = Transaction::with('products')
          ->where('transaction_id', $transaction_id)
          ->where('buyer_id', Auth::user()->user_id) 
          ->firstOrFail();

          return view('buyer.deposit', compact('transaction'));
    }

    public function processDeposit($transaction_id)
    {
        $transaction = Transaction::where('transaction_id', $transaction_id)
        ->where('buyer_id', Auth::user()->user_id)//ログインしているユーザと一致しているか確認(buyer_id)
        ->firstOrFail();

        $transaction->update([
            'status' => 2 

        ]);

        return redirect()->route('buyer.dashboard')->with('success', '入金が完了しました！管理者に通知されました。');
    }

    public function showcheck($transaction_id)
    {
        $transaction = Transaction::with('products')
          ->where('transaction_id', $transaction_id)
          ->firstOrFail();

          return view('buyer.check', compact('transaction'));
    }

    public function processCheck($transaction_id)
    {
        $transaction = Transaction::with('products')
          ->where('transaction_id', $transaction_id)
          ->firstOrFail();

           $transaction->update([
            'status' => 4,
            'delivered_at' => now()
    ]);

    // auth()->user()->increment('purchase_count');

          return redirect()->route('buyer.dashboard')->with('success', '受け取り確認完了しました。');
    }

    public function hideNotification($transaction_id)
{
    // 該当する取引を削除（または非表示フラグの更新）
    $transaction = Transaction::where('transaction_id', $transaction_id)
        ->where('buyer_id', Auth::user()->user_id) // ログイン中のユーザー本人か確認
        ->firstOrFail();
    
    $transaction->delete(); // 通知自体を削除する場合

    return redirect()->route('buyer.dashboard', ['tab' => 'notification'])
                     ->with('success', '通知を完全に削除しました。');
}
}


