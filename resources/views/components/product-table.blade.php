@props(['item', 'routeName' => 'buyer.show'])

<div style="width: 200px; border: 1px solid #ddd; border-radius: 5px; padding: 10px; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    
    <a href="{{ route($routeName, $item->product_id) }}" style="display: block; text-decoration: none;">
        <div style="text-align: center; height: 150px; display: flex; align-items: center; justify-content: center; background-color: #f9f9f9; margin-bottom: 10px;">
            @if($item->image_path)
                <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像" style="max-width: 100%; max-height: 100%; object-fit: cover;">
            @else
                <span style="color: #999;">画像なし</span>
            @endif
        </div>
    </a>
    
    <div style="font-size: 14px;">
        <div style="font-weight: bold; margin-bottom: 5px; font-size: 16px;">
            {{ $item->product_name }}
        </div>
        <div style="color: #e60000; font-weight: bold; margin-bottom: 5px;">
            {{ number_format($item->wish_price) }} 円
        </div>
        <div style="color: #666; font-size: 12px; margin-bottom: 5px;">
            出品者: {{ $item->yic_users->name ?? '退会済み' }}
        </div>
        <div style="color: #666; font-size: 12px; margin-bottom: 15px;">
            期限: {{ \Carbon\Carbon::parse($item->end_date)->format('Y/m/d H:i') }}
        </div>
        
        <div style="text-align: center;">
            <a href="{{ route($routeName, $item->product_id) }}" style="display: block; padding: 8px; background-color: #007bff; color: white; text-decoration: none; border-radius: 3px;">詳細を見る</a>
        </div>
    </div>
</div>