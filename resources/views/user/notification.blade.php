<x-user>
    <div class="container-center">
        {{-- カード自体を form で囲むことで、中身がすべて1枠に収まります --}}
        <form action="{{ route('user.notification.process', $transaction->transaction_id) }}" method="POST">
            @csrf
            <div class="notification-detail">
                <h2>以下の内容を確認しました。</h2>
                <p>商品名: <strong>{{ $transaction->products->product_name }}</strong></p>
                <p>買い手: {{ $transaction->yic_users->name }}</p>
                <p>落札金額: <strong class="price">{{ number_format($transaction->winnig_price) }} 円</strong></p>
                <p>落札日時: {{ \Carbon\Carbon::parse($transaction->won_at)->format('Y/m/d H:i') }}</p>
                
                {{-- 枠の中にボタンを配置 --}}
                <div style="margin-top: 20px; text-align: center;">
                    <button type="submit" class="ship-btn">発送</button>
                </div>
            </div>
        </form>
    </div>
</x-user>