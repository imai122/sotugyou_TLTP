<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>発送依頼</title>
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">
</head>
<body>
    <div class="container-center">
    <h2>発送依頼画面</h2>
    
    <div class="info-box">
        落札者ID: {{$transaction->buyer_id}}<br>
        商品名: <strong>{{ $transaction->products->product_name }}</strong><br>
        落札金額: {{number_format($transaction->winnig_price) }} 円
    </div>

    <form action="{{ route('admin.shop.shipping.process', $transaction->transaction_id) }}" method="POST">
        @csrf
        <button type="submit" class="btn-ship">出品者に発送依頼を送る</button>
    </form>
    </div>
</body>
</html>