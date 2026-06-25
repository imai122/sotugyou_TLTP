<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
</head>
<body style="padding: 20px; font-family: sans-serif;">
    
    <h2>商品一覧（ログイン不要）</h2>
    
    {{-- 💡 コントローラーから届いた複数の塊（$products）を、ループで1つずつ並べます --}}
    <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px;">
        @forelse ($products as $item)
            <div style="width: 200px; border: 1px solid #ddd; border-radius: 5px; padding: 10px; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                
                <a href="{{ route('buyer.show', $item->product_id) }}" style="display: block; text-decoration: none;">
                    <div style="text-align: center; height: 150px; display: flex; align-items: center; justify-content: center; background-color: #f9f9f9; margin-bottom: 10px;">
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像" style="max-width: 100%; max-height: 100%; object-fit: cover;">
                        @else
                            <span style="color: #999;">画像なし</span>
                        @endif
                    </div>
                </a>
                
                <div style="font-size: 14px;">
                    <div style="font-weight: bold; margin-bottom: 5px; font-size: 16px; color: #333;">
                        {{ $item->product_name }}
                    </div>
                    <div style="color: #e60000; font-weight: bold; margin-bottom: 5px;">
                        {{ number_format($item->wish_price) }} 円
                    </div>
                    <div style="color: #666; font-size: 12px; margin-bottom: 15px;">
                        期限: {{ $item->end_date }}
                    </div>
                    
                    <div style="text-align: center;">
                        <a href="{{ route('buyer.show', $item->product_id) }}" style="display: block; padding: 8px; background-color: #007bff; color: white; text-decoration: none; border-radius: 3px;">詳細を見る</a>
                    </div>
                </div>
            </div>
        @empty
            <p>現在、出品されている商品はありません。</p>
        @endforelse
    </div>

    <div style="margin-top: 30px;">
        <a href="{{ route('user.login.index') }}" style="padding: 10px 20px; background-color: #666; color: white; text-decoration: none; border-radius: 3px;">ログイン画面へ</a>
    </div>

</body>
</html>