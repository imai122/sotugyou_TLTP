<x-buyer>
    <div class="buyer-container">
        <h2 style="text-align: center; margin-bottom: 20px;">入札ページ</h2>

        <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 500px; margin: 0 auto;">
            
            <form action="{{ route('buyer.bids.store', $product->product_id) }}" method="POST">
                @csrf
                
                {{-- 入札金額 --}}
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px;">入札金額</label>
                    <input type="number" name="bid_amount" min="{{ $product->wish_price }}" required 
                           style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                    <p style="font-size: 0.85rem; color: #e60000; margin-top: 5px;">
                        ※希望価格 ({{ number_format($product->wish_price) }}円) 以上で入力してください
                    </p>
                </div>

                <div style="text-align: center;">
                    <button type="submit" class="btn-action btn-blue" style="width: 100%; padding: 12px;">登録</button>
                </div>
            </form>

        </div>
    </div>
</x-buyer>