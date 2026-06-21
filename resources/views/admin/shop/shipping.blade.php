<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
        落札者ID: {{$transaction->buyer_id}}<br>
        商品名: <strong>{{  $transaction->products->product_name }}</strong><br>
        落札金額: {{number_format($transaction->winnig_price) }} 円
    </div>
    <form action="{{ route('admin.shop.shipping.process', $transaction->transaction_id) }}" method="POST">
        @csrf
        <button type="submit" style="padding: 10px 20px; background: green; color: white; font-weight: boid;">
            出品者に発送依頼を送る
        </button>
    </form>
        
</body>
</html>