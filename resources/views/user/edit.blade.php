<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品説明の修正</title>
</head>
<body style="padding: 20px;">
    <h2>商品説明の修正</h2>
    
    <div style="margin-bottom: 20px; padding: 10px; background-color: #f9f9f9; border: 1px solid #ddd;">
        <p><strong>商品名:</strong> {{ $product->product_name }}</p>
        <p><strong>希望価格:</strong> {{ $product->wish_price }}円</p>
    </div>

    <form action="{{ route('user.update', $product->product_id) }}" method="POST">
        @csrf
        @method('PUT') <div>
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">商品説明（ここだけ編集可能）</label>
            <textarea name="comment" rows="5" cols="50" required>{{ old('comment', $product->comment) }}</textarea>
            @error('comment')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-top: 15px;">
            <button type="submit" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer;">更新する</button>
            <a href="{{ route('user.dashboard', ['tab' => 'history']) }}" style="margin-left: 10px;">キャンセル</a>
        </div>
    </form>
</body>
</html>