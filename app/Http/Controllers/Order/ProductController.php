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
          $data = $this->getSharedData();

        return view('buyer.show', $data);
    }

     public function create(): View
    {
       $data = $this->getSharedData();


        return view('buyer.dashboard', $data);

    }

    public function getSharedData(): array
    {
        $yic_users = YIC_user::all();

        $my_bids = Bid::with('products')//入札商品取得
            ->where('bidder_id', auth()->id())//自分のbidder_idの情報取得
            ->orderBy('bid_at', 'desc') // 新しい登録順
            ->get();

        // ログインしているユーザーの履歴(商品登録している人のみ)
        $products = Product::where('seller_id', auth()->id())->get();

        // 閲覧タブ（自分以外の情報）
        $other_products = Product::with('yic_users') 
            ->where('seller_id', '!=', auth()->id())
            ->whereHas('yic_users', function ($query) { 
                $query->where('role', 3); // 出品者のみ
            })
            ->orderBy('created_at', 'desc') // 新しい順
            ->get();

        
        return compact('yic_users', 'products', 'other_products' ,'my_bids');
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

  public function show($product_id): View
    {
        $product = \App\Models\Product::with('categories')->findOrFail($product_id);
        
        return view('buyer.show', compact('product'));
    }
      
    public function dashboard()
    {
        $my_bids = Bid::with('products')//入札商品取得
        ->where('bidder_id', auth()->id())//自分のbidder_idの情報取得
        ->orderBy('bid_at', 'desc') // 新しい登録順
        ->get();

         $other_products = Product::with('yic_users') 
         ->where('seller_id', '!=', auth()->id())
         ->whereHas('yic_users', function ($query) { 
         $query->where('role', 3); // 出品者のみ
         })
        ->orderBy('created_at', 'desc') // 新しい順
        ->get();

       
        $won_transactions = Transaction::with('products')
        ->where('buyer_id', auth()->user()->name)
        // ->where('buyer_id', auth()->user()->user_id)
        // ->where('status',1)
        ->orderBy('won_at', 'desc')
        ->get();

        return view('buyer.dashboard', compact('other_products', 'my_bids', 'won_transactions'));
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

          return redirect()->route('buyer.dashboard')->with('success', '受け取り確認完了しました。');
    }
}


