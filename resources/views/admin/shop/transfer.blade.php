<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>振込内容の確認</title>
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">
</head>
<body>

    <div class="transfer-container">
        <h2>振込内容の確認</h2>

        @php
            $price = $transaction->winnig_price; 
            $fee = floor($price * 0.1);
            $payoutAmount = $price - $fee;
        @endphp

        <div class="info-group">
            <div class="label">商品名</div>
            <div class="value">{{ $transaction->products->product_name }}</div>
        </div>

        <div class="info-group">
            <div class="label">落札金額</div>
            <div class="value">{{ number_format($price) }} 円</div>
        </div>
        
        <div class="info-group">
            <div class="label">手数料 (10%)</div>
            <div class="value" style="color: #666;">- {{ number_format($fee) }} 円</div>
        </div>
        
        <div class="payout-box">
            <div class="payout-label">振込金額</div>
            <div class="payout-amount">{{ number_format($payoutAmount) }} 円</div>
        </div>

        <form action="{{ route('admin.shop.transfer.process', $transaction->transaction_id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-submit">この金額で入金処理を完了する</button>
        </form>
    </div>

</body>
</html>