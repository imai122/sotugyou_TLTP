<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品閲覧</title>
</head>
<body>
    <div style="padding: 20px; font-family: sans-serif;">
    <h2>商品詳細情報</h2>
    </div>
    
    <div style="max-width: 600px; border: 1px solid #ddd; padding: 20px; background-color: #f9f9f9;">
        
        <div style="text-align: center; margin-bottom: 20px;">
            @if($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}" alt="商品画像" style="max-width: 300px; border: 1px solid #ccc;">
            @else
                <div style="padding: 50px; background: #eee; color: #666;">画像なし</div>
            @endif
        </div>

        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <tr style="border-bottom: 1px solid #ddd;">
                <th style="padding: 10px; width: 30%;">商品ID</th>
                <td style="padding: 10px;">{{ $product->product_id }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #ddd;">
                <th style="padding: 10px;">商品名</th>
                <td style="padding: 10px; font-weight: bold;">{{ $product->product_name }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #ddd;">
                <th style="padding: 10px;">カテゴリ</th>
                <td style="padding: 10px;">
            {{ $product->categories ? $product->categories->category_name : '未分類' }}
                </td>
            </tr>
            <tr style="border-bottom: 1px solid #ddd;">
                <th style="padding: 10px;">希望価格</th>
                <td style="padding: 10px; color: #e60000; font-weight: bold;">{{ number_format($product->wish_price) }} 円</td>
            </tr>
            <tr style="border-bottom: 1px solid #ddd;">
                <th style="padding: 10px;">落札期限</th>
                <td style="padding: 10px;">{{ $product->end_date }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #ddd;">
                <th style="padding: 10px;">ステータス</th>
                <td style="padding: 10px;">{{ $product->status }}</td>
            </tr>
            <tr>
                <th style="padding: 10px; vertical-align: top;">商品説明</th>
                <td style="padding: 10px; white-space: pre-wrap;">{{ $product->comment }}</td>
            </tr>
        </table>
    </div>


    <div style="margin-top: 20px;">
        <a href="{{ route('buyer.dashboard', ['tab' => 'product-show']) }}" style="padding: 10px 20px; background-color: #666; color: white; text-decoration: none; border-radius: 3px;">一覧に戻る</a>
    </div>


</div>
</body>
</html>