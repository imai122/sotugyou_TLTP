<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <body>
    <h1>登録フォーム</h1>

    @if ($errors->any())
        <!-- <div style="color: red; margin-bottom: 20px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div> -->
        <x-error-messages :errors="$errors" />
    @endif

    <form action="{{ route('buyer.bids.store', $product->product_id) }}" method="POST">
        @csrf
   
    <div>
        <label>入札金額</label>
        <input type="number" name="bid_amount" min="{{ $product->wish_price }}" required>
        <p style="font-size: 12px; color: #666;">希望価格 ({{ number_format($product->wish_price) }}円)以上で入力してください</p>
   </div>

     <div>
        <input type="submit" value="登録">
    </div>
    </form>
    
</body>
</html>