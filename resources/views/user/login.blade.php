<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
</head>
<body>
    <main>
        <h1>ログイン画面</h1>
        @if($errors->any())
        <div style="color:red">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="{{route('user.login.process')}}" method="POST">
            @csrf
           <div>
        <label>ID:</label>
        <input type="text" name="user_id" value="{{ old('user_id') }}" autocomplete="new-text">
        </div>
        <div>
        <label>パスワード:</label>
        <input type="password" name="password" autocomplete="new-password">
        </div>
            <div>
            <input type="submit" value="送信">
            </div>
            <div style="display: flex; gap: 10px;">
              <a href="{{ route('user.create') }}" style="text-decoration: none;">
              <button type="button">新規登録へ</button>
              </a>

              <a href="{{ route('buyer.show')  }}" style="text-decoration: none;">
                <button type="button">ログインせずに商品を閲覧する</button>
              </a>
            </div>
        </form>
    </main>
    
</body>
</html>