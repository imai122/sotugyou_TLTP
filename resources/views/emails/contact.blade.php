<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
</head>
<body>
    @if(isset($type) && $type === 'seller')
        <h2>出品した商品が落札されました</h2>
        <p>あなたの出品した商品が落札されました。取引の準備を進めてください。</p>
    @else
        <h2>落札おめでとうございます！</h2>
        <p>以下の商品をあなたが最高額で落札しました。</p>
    @endif

    <hr>
    <ul>
        <li><strong>商品名:</strong> {{ $product->product_name }}</li>
        <li><strong>落札金額:</strong> {{ number_format($transaction->winnig_price) }} 円</li>
        <li><strong>落札日時:</strong> {{ \Carbon\Carbon::parse($transaction->won_at)->format('Y/m/d H:i') }}</li>
    </ul>
    <hr>

    <p>マイページの「通知」タブから詳細を確認し、取引を進めてください。</p>
</body>
</html>