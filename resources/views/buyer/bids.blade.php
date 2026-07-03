<x-buyer>
    <div class="buyer-container">
        <h2 style="text-align: center; margin-bottom: 20px;">入札フォーム</h2>

        @if ($errors->any())
            <x-error-messages :errors="$errors" />
        @endif

        {{-- 白いカードのデザイン --}}
        <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 500px; margin: 0 auto;">
            
            <form action="{{ route('buyer.bids.store', $product->product_id) }}" method="POST">
                @csrf
           
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: bold; color: #374151; margin-bottom: 10px;">入札金額</label>
                    
                    <input type="number" name="bid_amount" min="{{ $product->wish_price }}" required 
                           style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 1.1rem; box-sizing: border-box;">
                    
                    <p style="font-size: 0.85rem; color: #dc2626; margin-top: 10px; font-weight: bold;">
                        ※希望価格 ({{ number_format($product->wish_price) }}円) 以上で入力してください
                    </p>
               </div>

                <div style="text-align: center; margin-top: 30px;">
                    <button type="submit" class="btn-action btn-blue" style="width: 100%; font-size: 1.1rem; padding: 12px;">入札を確定する</button>
                </div>
                
                <div style="text-align: center; margin-top: 20px;">
                    <a href="javascript:history.back()" style="color: #64748b; text-decoration: none; font-weight: bold;">戻る</a>
                </div>
            </form>

        </div>
    </div>
</x-buyer>