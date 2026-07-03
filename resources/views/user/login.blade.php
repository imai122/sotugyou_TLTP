<x-login>
    <div class="login-wrapper">
        <main class="login-card">
            <h1>ログイン画面</h1>

            <x-flash-message />

            @if($errors->any())
                <x-error-messages :errors="$errors" />
            @endif

            <form action="{{ route('user.login.process') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="user_id">ID:</label>
                    <input type="text" id="user_id" name="user_id" value="{{ old('user_id') }}" autocomplete="new-text">
                </div>
                
                <div class="form-group">
                    <label for="password">パスワード:</label>
                    <input type="password" id="password" name="password" autocomplete="new-password">
                </div>
                
                <div>
                    <input type="submit" value="送信" class="submit-btn">
                </div>
                
                <div class="action-links">
                   <a href="{{ route('user.register') }}">
                   <button type="button" class="action-btn">新規登録へ</button>
                   </a>
                   <a href="{{ route('user.reissue.index') }}">
                   <button type="button" class="action-btn">ID・パスワードをお忘れの方はこちら</button>
                   </a>
                    </div>
            </form>
        </main>
    </div>
</x-login>