<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>振込内容の確認</h2>

    <div style="padding: 20px; border: 1px solid #ccc; width: 300px;">
        <p>商品名: {{ $transaction->products->product_name }}</p>
        <p>落札金額: {{ number_format($transaction->winnig_price) }} 円</p>
        
        <div style="color: #666; font-size: 0.9em; margin-bottom: 10px;">
            手数料 (10%): - {{ number_format($transaction->winning_price * 0.1) }} 円
        </div>
        
        <hr>
        <p style="font-weight: bold; font-size: 1.2em; color: #e60000;">
            振込金額: {{ number_format($transaction->payout_amount) }} 円
        </p>
    </div>

    <form action="{{ route('admin.shop.transfer.process', $transaction->transaction_id) }}" method="POST" style="margin-top: 20px;">
        @csrf
        <button type="submit" style="padding: 10px 20px; background: green; color: white; border: none; font-weight: bold; cursor: pointer;">
            この金額で入金処理を完了する
        </button>
    </form>
</body>
</html>