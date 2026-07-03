<x-create>

    <h1>登録フォーム</h1>

    @if($errors->any())
        <x-error-messages :errors="$errors" />
    @endif

    <form action="{{ route('user.register.store') }}" method="POST">
        @csrf

        <div>
           <label>名前 <span class="required">※この項目は必須です</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <label>住所 <span class="required">※この項目は必須です</span></label>
            <input type="text" name="address" value="{{ old('address') }}">
        </div>

        <div>
            <label>郵便番号 <span class="required">※この項目は必須です</span></label>
            <input type="text" name="postal_code" value="{{ old('postal_code') }}" required>
        </div>

        <div>
            <label>電話番号 <span class="required">※この項目は必須です</label>
            <input type="text" name="phone_number" value="{{ old('phone_number') }}" required>
        </div>

        <div>
            <label>メールアドレス <span class="required">※この項目は必須です</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label>振込先 <span class="required">※この項目は必須です</label>
            <input type="text" name="bank_account" value="{{ old('bank_account') }}" required>
        </div>

        <div>
            <label>選択: <span class="required">※この項目は必須です</span></label>
            <select name="role"  value="{{ old('role') }}"required>
                <option value="3">出品者</option>
                <option value="4">買い手</option>
            </select>
        </div>

        <div>
            <label>ID <span class="required">※この項目は必須です</label>
            <input type="text" name="user_id" value="{{ old('user_id') }}" autocomplete="off" required>
        </div>

        <div>
            <label>パスワード <span class="required">※この項目は必須です</label>
            <input type="password" name="password" value="{{ old('password') }}" autocomplete="new-password" required>
        </div>

        <div>
            <label>パスワード(確認) <span class="required">※この項目は必須です</label>
            <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" autocomplete="new-password" required>
            @error('password')
            <div style="color: red; font-size: 0.9em; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <input type="submit" value="登録">
        </div>

    </form>

</x-create>