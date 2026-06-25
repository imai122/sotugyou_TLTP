<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\YSRRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
//use Pest\Support\View;
use Illuminate\View\View;
use App\Models\YIC_user;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Bid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OrderController extends Controller
{
    //
    public function create(): View
    {
        return view('user.create');
    }

    

     public function  loginstore(CreateRequest $request): RedirectResponse
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
        'listing_count'  => 0,
        'purchase_count' => 0,
        ]);
    return redirect()->route('user.login.index');

     }


     public function storeProduct(ProductRequest $request): RedirectResponse
     {
        $validated = $request->validated();
         
        $products = new Product();

        $products->category_id = $validated['category_id'];
        //$products->product_id = $validated['product_id'];
        $products->product_name = $validated['product_name'];
        $products->comment = $validated['comment'];
        $products->wish_price = $validated['wish_price'];
        $products->end_date = $validated['end_date'];

        if ($request->hasFile('image_path')) {
        $path = $request->file('image_path')->store('products', 'public');//写真を表示させる
        $products->image_path = $path;
    }
       $products->seller_id = auth()->id();
       $products->status = '出品中';

       if ($request->hasFile('image_path')) {
        // 'products' ディレクトリに保存し、そのパスを取得
        $path = $request->file('image_path')->store('products', 'public');
        // データベースにはそのパス（文字列）を保存
        $products->image_path = $path;
    }

        $products->save();

        auth()->user()->increment('listing_count');

        return redirect()->route('user.dashboard', ['tab' => 'history'])
                     ->with('success', '商品を登録しました');
     }



     public function dashboard(): View
{
    $userId = auth()->id();
    $loginUserId = auth()->user()->user_id;

    $query = Product::where('seller_id', $userId);

    if (request()->filled('name')) {
        $query->where('product_name', 'LIKE', '%' . request('name') . '%');
    }

    $products = $query->get();

    $other_products = Product::with('yic_users')
        ->where('seller_id', '!=', $userId)
        ->whereHas('yic_users', function ($query) {
            $query->where('role', 3);
        })

        ->where('end_date', '>', now())
        ->orderBy('created_at', 'desc')
        ->get();

    
    // $userId = auth()->user()->user_id;
    $won_transactions = Transaction::with('products')
        ->where('buyer_id', $loginUserId)
        ->orderBy('won_at', 'desc')
        ->get();


        $sold_transactions = Transaction::with('products')
        ->whereHas('products', function ($query) use ($userId) {
            $query->where('seller_id', $userId);
        })
        ->get();

        $listing_count = $products->count();

        return view('user.dashboard', compact(
            'products', 
            'other_products', 
            'won_transactions', 
            'sold_transactions', 
            'listing_count', 
        ));
}

    public function edit($id): View
    {
        $product = Product::findOrFail($id);
        return view('user.edit', compact('product'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'comment' => 'required|string|max:100'
        ]);

        $product = Product::findOrFail($id);
        $product->comment = $request->comment;
        $product->save();

        return redirect()->route('user.dashboard', ['tab' => 'history'])
        ->with('success', '商品のコメントを削除しました。');
    }

    public function destroy($id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('user.dashboard', ['tab' => 'history'])
        ->with('success', '商品を削除しました。');
    }

    public function show($product_id): View
    {
        $product = Product::with('categories')->findOrFail($product_id);
        
        return view('user.show', compact('product'));
    }

    public function shownotifiction($transaction_id)
    {
          $transaction = Transaction::with('products')
          ->where('transaction_id', $transaction_id)
          ->firstOrFail();

          return view('user.notification', compact('transaction'));
    }

    public function processNotification($transaction_id)
    {
        $transaction = Transaction::where('transaction_id', $transaction_id)
        ->firstOrFail();

        $transaction->update([
            'status' => 3,
            'payment_received_at' => now()
        ]);

        return redirect()->route('user.dashboard')->with('success', '発送完了しました。');
    }

    }




    
    








