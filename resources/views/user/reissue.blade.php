<x-login>
    <div class="login-wrapper">
        <main class="login-card">
            <h1>ID・パスワード再発行</h1>

            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 20px;">
                ご登録のメールアドレスを入力してください。<br>
                ID・新しいパスワードをメールにてお送りします。
            </p>

            @if($errors->any())
                <x-error-messages :errors="$errors" />
            @endif

            <form action="{{ route('user.reissue.process') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">メールアドレス:</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email">
                </div>

                <div>
                    <input type="submit" value="再発行する" class="submit-btn">
                </div>

                <div class="action-links">
                    <a href="{{ route('user.login.index') }}">
                        <button type="button" class="action-btn">ログイン画面へ戻る</button>
                    </a>
                </div>
            </form>
        </main>
    </div>
</x-login>