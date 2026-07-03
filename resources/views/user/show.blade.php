<x-user>
    <h2>商品詳細情報</h2>

    <div class="detail-wrapper">
        <div class="detail-image">
            @if($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}" alt="商品画像">
            @else
                <div class="detail-image-placeholder">画像なし</div>
            @endif
        </div>

        <table class="detail-table">
            <tr>
                <th>商品ID</th>
                <td>{{ $product->product_id }}</td>
            </tr>
            <tr>
                <th>商品名</th>
                <td><strong>{{ $product->product_name }}</strong></td>
            </tr>
            <tr>
                <th>カテゴリ</th>
                <td>{{ $product->categories ? $product->categories->category_name : '未分類' }}</td>
            </tr>
            <tr>
                <th>希望価格</th>
                <td class="price">{{ number_format($product->wish_price) }} 円</td>
            </tr>
            <tr>
                <th>落札期限</th>
                <td>{{ \Carbon\Carbon::parse($product->end_date)->format('Y-m-d H:i') }}</td>
            </tr>
            <tr>
                <th>ステータス</th>
                <td>{{ $product->status }}</td>
            </tr>
            <tr>
                <th>商品説明</th>
                <td>{{ $product->comment }}</td>
            </tr>
        </table>
    </div>

    <a href="{{ route('user.dashboard', ['tab' => 'history']) }}" class="detail-back-btn">一覧に戻る</a>
</x-user>