<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
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

     public function index(): View
     {
        $bids = Bid::with(['yic_users', 'products'])->get();
        $products = Product::with(['yic_users'])->get();
        return view('admin.shop.dashboard', compact('bids', 'products'));

     }

     public function edit($product_id): View
     {
         $products = Product::findOrFail($product_id);
         return view('admin.shop.edit', compact('products'));
     }

     public function update(Request $request, $product_id)
     {
         $products = Product::findOrFail($product_id);
         $products->update($request->only(['product_name', 'comment']));
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
}
    
