<x-buyer>
    <link rel="stylesheet" href="{{ asset('css/buyer.css') }}">
    
    <div class="container-center">
        <h2>入金確認</h2>
        <div class="deposit-container">
            <p>商品名: <strong>{{ $transaction->products->product_name }}</strong></p>
            <p>落札金額: {{ number_format($transaction->winnig_price) }} 円</p>
            <p>落札日時: {{ \Carbon\Carbon::parse($transaction->won_at)->format('Y/m/d H:i') }}</p>
        </div>
        
        <form action="{{ route('buyer.deposit.process', $transaction->transaction_id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-confirm">入金を確定する</button>
        </form>
    </div>
</x-buyer>