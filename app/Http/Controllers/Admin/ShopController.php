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

     public function update(Request $request, $product_id)
     {
         $products = Product::findOrFail($product_id);
         $products->update($request->only(['product_name', 'comment', 'product_id']));
         return redirect()->route('admin.shop.dashboard')
         ->with('success', '更新しました。');
     }   
     
     public function destroy ($product_id): RedirectResponse
     {
        $products = Product::findOrFail($product_id);
        $products->delete();
        return redirect()->route('admin.shop.dashboard')
        ->with('error', '削除しました。');
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
    
