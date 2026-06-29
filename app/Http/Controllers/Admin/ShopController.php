<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\YIC_user;
use App\Models\Product;
use App\Models\Bid;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;


class ShopController extends Controller
{

     public function index(Request $request): View
     {

     $bidderId = $request->input('bidder_id');//買い手で検索
     $productId = $request->input('product_name');//商品名で検索

     $bidsQuery = Bid::with(['yic_users', 'products']);//入札情報検索
     if (!empty($bidderId)){
          $bidsQuery->whereHas('yic_users', function($q) use ($bidderId){
               $q->where('bidder_id', 'LIKE', '%' . $bidderId . '%');
          });
     }
        $bids = $bidsQuery->get();

        $productsQuery = Product::with(['yic_users']);//商品情報検索
        if (!empty($productId)){
          $productsQuery->where('product_name', 'LIKE', '%' . $productId . '%');
        }
        $products = $productsQuery->get();
        $transactions = Transaction::with('products')->orderBy('updated_at', 'desc')->get();


        return view('admin.shop.dashboard', compact('bids', 'products', 'transactions'));

     }

     public function edit($product_id): View
     {
         $products = Product::findOrFail($product_id);
         return view('admin.shop.edit', compact('products'));
     }

     public function update(Request $request, $product_id )
     {
     $products = Product::findOrFail($product_id);
    $isCompleted = $products->transactions()->where('status', 5)->exists();

    if ($isCompleted) {
        return redirect()->back()->with('error', '取引完了のため、修正できません。');
    }
    
    $products->update($request->only(['product_name', 'comment']));
    return redirect()->route('admin.shop.dashboard')->with('success', '更新しました。');
}

// ShopController.php の destroy メソッド
public function destroy($product_id): RedirectResponse
{
    $product = Product::findOrFail($product_id);
    
    // ステータス5の取引が「存在する」かチェック
    $isCompleted = $product->transactions()->where('status', 5)->exists();

    if ($isCompleted) {
        return redirect()->back()->with('error', '取引完了のため、削除できません。');
    }

    $product->delete();
    return redirect()->route('admin.shop.dashboard')->with('success', '削除しました。');
     }   
     
//      public function destroy ($product_id): RedirectResponse
//      {
//         $products = Product::findOrFail($product_id);
//         $transaction = $products->transaction;
//         if ($transaction && $transaction->status == 5) {
//         return redirect()->back()->with('error', '取引完了のため、削除できません。');
//     }
//         $products->delete();
//         return redirect()->route('admin.shop.dashboard')
//         ->with('error', '削除しました。');
//      }


     public function destroyBid($bid_id)
{
    $bid = Bid::findOrFail($bid_id);
    
    // 取引が存在し、かつステータスが5の場合は削除不可
    if ($bid->products->transaction && $bid->products->transaction->status == 5) {
        return redirect()->back()->with('error', '取引完了のため、入札情報の削除はできません。');
    }

    $bid->delete();
    return redirect()->route('admin.shop.dashboard', ['tab' => 'bids-product'])->with('success', '入札情報を削除しました。');
}

public function updateBid(Request $request, $bid_id)
{
    $bid = Bid::findOrFail($bid_id);

    // 取引が存在し、かつステータスが5の場合は編集不可
    if ($bid->products->transaction && $bid->products->transaction->status == 5) {
        return redirect()->back()->with('error', '取引完了のため、入札情報の修正はできません。');
    }

    // 入札金額の更新など
    $bid->update($request->only(['bid_amount']));
    return redirect()->route('admin.shop.dashboard', ['tab' => 'bids-product'])->with('success', '入札情報を更新しました。');
}

     public function sendContact (Request $request, $product_id)
     {

        $product = Product::findOrFail($product_id);
        $max_product = Bid::where('product_id', $product_id)
        ->orderBy('bid_amount', 'desc')
        ->first();

        $buyer= YIC_user::where('user_id', $max_product->bidder_id)
        ->first();

        
       $transaction = Transaction::create([
            'transaction_id' => $this->generateTransactionId(), 
            'product_id'     => $product_id,
            'buyer_id'       => $max_product->bidder_id,
            'winning_price'  => $max_product->bid_amount, 
            'status'         => 1,                        
            'won_at'         => now(),
        ]);

        $seller = YIC_user::where('user_id', $product->seller_id)
        ->first();

        Mail::to($buyer->bidder_id)->send(new ContactMail($transaction, $product));

     }

     public function showshipping($transaction_id)
     {
          $transaction = Transaction::with('products')
          ->where('transaction_id', $transaction_id)
          ->firstOrFail();

          return view('admin.shop.shipping', compact('transaction'));
     }

     public function processShipping($transaction_id)
     {
          $transaction = Transaction::with('products')
          ->where('transaction_id', $transaction_id)
          ->firstOrFail();

         

          return redirect()->route('admin.shop.dashboard')
          ->with('success', '発送依頼が完了しました。');
     }

     public function showtransfer($transaction_id)
     {
         $transaction = Transaction::with('products')
          ->where('transaction_id', $transaction_id)
          ->firstOrFail();

          return view('admin.shop.transfer', compact('transaction'));
     }



     public function processTransfer($transaction_id)
     {
        $transaction = Transaction::where('transaction_id', $transaction_id)
        ->firstOrFail();

        $commissionRate = 0.10;
        $payoutAmount = $transaction->winning_price * (1 - $commissionRate);

       $transaction->update([
        'status' => 5,
        'payout_amount' => $payoutAmount,
        'payout_completed_at' => now(), 
    ]);

    return redirect()->route('admin.shop.dashboard')->with('success', '振り込み完了しました。');
     }
}
    
