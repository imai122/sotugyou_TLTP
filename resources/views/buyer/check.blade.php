<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>受け取りが確認されたら確認してください</h2>
        商品名: <strong>{{ $transaction->products->product_name }}</strong><br>
        落札者: {{ $transaction->yic_users->name}}<br>
        振り込み銀行: {{$transaction->yic_users->bank_account}}<br>
        落札金額: {{ number_format($transaction->winnig_price) }} 円<br>
        落札日時: {{ \Carbon\Carbon::parse($transaction->won_at)->format('Y/m/d H:i') }}
        </div>
      <form action="{{ route('buyer.check.process', $transaction->transaction_id) }}" method="POST">
      @csrf
      <button type="submit" style="padding: 10px 20px; background: green; color: white; font-weight: bold;">
                受け取り確認完了
    </button>
      </form>
</body>
</html>