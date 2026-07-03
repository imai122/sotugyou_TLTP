<x-user>

    <h1>情報修正</h1>

    <form action="{{ route('admin.system.update', $user->user_id) }}" method="POST" class="admin-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>名前</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}">
        </div>

        <div class="form-group">
            <label>住所</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}">
        </div>

        <div class="form-group">
            <label>電話番号</label>
            <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
        </div>

        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">修正する</button>
            <a href="{{ route('admin.system.dashboard') }}" class="btn-back">戻る</a>
        </div>
        
    </form>
    
</x-user>