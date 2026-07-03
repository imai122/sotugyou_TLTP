<x-user>
    <h2>商品説明の修正</h2>

    <div class="edit-info">
        <p><strong>商品名:</strong> {{ $product->product_name }}</p>
        <p><strong>希望価格:</strong> {{ $product->wish_price }}円</p>
    </div>

    <form action="{{ route('user.update', $product->product_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label class="edit-label">商品説明（ここだけ編集可能）</label>
            <textarea name="comment" rows="5" class="edit-textarea" required>{{ old('comment', $product->comment) }}</textarea>
            @error('comment')
                <div class="edit-error">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-top: 15px;">
            <button type="submit" class="edit-submit-btn">更新する</button>
            <a href="{{ route('user.dashboard', ['tab' => 'history']) }}" class="edit-cancel-link">キャンセル</a>
        </div>
    </form>
</x-user>