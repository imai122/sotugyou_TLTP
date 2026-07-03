<x-buyer>
    <div class="buyer-container">
        <h2 style="text-align: center; margin-bottom: 20px;">受け取り確認</h2>

        <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 500px; margin: 0 auto;">
            
            <p style="text-align: center; color: #155724; font-weight: bold; margin-bottom: 20px;">
                商品が届き、内容に問題がないことを確認してください。
            </p>

            {{-- 取引内容の表示枠 --}}
            <div style="background-color: #f8fafc; padding: 20px; border-radius: 6px; border: 1px solid #e2e8f0; margin-bottom: 25px;">
                <p style="margin: 0 0 10px 0;">商品名: <strong>{{ $transaction->products->product_name }}</strong></p>
                <p style="margin: 0 0 10px 0;">落札者: {{ $transaction->yic_users->name }}</p>
                <p style="margin: 0 0 10px 0;">振込先銀行: {{ $transaction->yic_users->bank_account }}</p>
                <p style="margin: 0 0 10px 0;">落札金額: <span style="color: #e60000; font-weight: bold;">{{ number_format($transaction->winnig_price) }} 円</span></p>
                <p style="margin: 0; color: #666; font-size: 0.9rem;">落札日時: {{ \Carbon\Carbon::parse($transaction->won_at)->format('Y/m/d H:i') }}</p>
            </div>

            <form action="{{ route('buyer.check.process', $transaction->transaction_id) }}" method="POST" style="text-align: center;">
                @csrf
                <button type="submit" class="btn-action btn-blue" style="width: 100%; font-size: 16px; margin-bottom: 15px;">
                    受け取り確認完了
                </button>
                
                <a href="javascript:history.back()" style="color: #666; text-decoration: none; font-size: 0.95rem;">戻る</a>
            </form>
        </div>
    </div>
</x-buyer>