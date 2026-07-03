<x-buyer>
    <div class="buyer-container">
        <h2 style="border-left: 5px solid #3b82f6; padding-left: 15px; margin-bottom: 30px; color: #1f2937;">商品一覧（ログイン不要）</h2>
       
        <div class="product-grid" style="display: flex; flex-wrap: wrap; gap: 20px;">
            @forelse ($products as $item)
                <div class="product-card" style="width: 220px; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: transform 0.2s;">
                    
                    <a href="{{ route('buyer.show', $item->product_id) }}" style="display: block; text-decoration: none;">
                        <div style="text-align: center; height: 160px; display: flex; align-items: center; justify-content: center; background-color: #f8fafc; margin-bottom: 15px; border-radius: 4px; overflow: hidden;">
                            @if($item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像" style="max-width: 100%; max-height: 100%; object-fit: cover;">
                            @else
                                <span style="color: #94a3b8; font-weight: bold;">画像なし</span>
                            @endif
                        </div>
                    </a>
                    
                    <div style="font-size: 14px;">
                        <div style="font-weight: bold; margin-bottom: 8px; font-size: 1.1rem; color: #1f2937; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $item->product_name }}
                        </div>
                        <div style="color: #dc2626; font-weight: bold; margin-bottom: 8px; font-size: 1.1rem;">
                            {{ number_format($item->wish_price) }} 円
                        </div>
                        <div style="color: #64748b; font-size: 0.85rem; margin-bottom: 15px;">
                            期限: {{ \Carbon\Carbon::parse($item->end_date)->format('Y/m/d H:i') }}
                        </div>
                        
                        <div style="text-align: center;">
                            <a href="{{ route('buyer.show', $item->product_id) }}" class="btn-action btn-blue" style="display: block; padding: 10px; width: 100%; box-sizing: border-box;">詳細を見る</a>
                        </div>
                    </div>
                </div>
            @empty
                <div style="width: 100%; padding: 50px; text-align: center; background: #fff; border-radius: 8px; color: #64748b; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    現在、出品されている商品はありません。
                </div>
            @endforelse
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="{{ route('user.login.index') }}" class="btn-action" style="background-color: #475569; padding: 12px 30px; font-size: 1.1rem; border-radius: 30px;">ログイン画面へ</a>
        </div>
    </div>
</x-buyer>