<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
        <h2>以下の内容が確認しました。</h2>
        商品名: <strong>{{ $transaction->products->product_name }}</strong><br>
        買い手: {{ $transaction->yic_users->name}}
        落札金額: {{ number_format($transaction->winnig_price) }} 円<br>
        落札日時: {{ \Carbon\Carbon::parse($transaction->won_at)->format('Y/m/d H:i') }}
    </div>
      <form action="{{ route('user.notification.process', $transaction->transaction_id) }}" method="POST">
      @csrf
      <button type="submit" style="padding: 10px 20px; background: green; color: white; font-weight: bold;">
                発送
    </button>
    </form>
</body>
</html>