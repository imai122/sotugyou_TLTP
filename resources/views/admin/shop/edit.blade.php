<x-create>
    <form action="{{ route('admin.shop.update', $products->product_id) }}" method="POST" class="admin-form">
        @csrf
        @method('PUT')
        
        <h1>出品情報修正</h1>

        <div class="form-group">
            <label>商品名</label>
            <input type="text"  name="product_name" value="{{ old('product_name', $products->product_name) }}">
        </div>

        <div class="form-group">
        <label>商品ID</label>
        <input type="text" value="{{ $products->product_id }}" readonly style="background-color: #f0f0f0; border: none;">
        </div>

        <div class="form-group">
            <label>商品コメント</label>
            <input type="text" name="comment" value="{{ old('comment', $products->comment) }}" >
        </div>

        <div class="form-group">
            <label>商品金額</label>
            <input type="text"  value="{{ $products->wish_price }}" readonly style="background-color: #f0f0f0; border: none;">
        </div>
        

        <div class="form-actions">
            <button type="submit">修正する</button>
            <a href="{{ route('admin.shop.dashboard') }}">キャンセル</a>
        </div>
    </form>
</x-create>