<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
</head>
<body>
    <h1>登録フォーム</h1>

    <form action="{{route('user.procss')}}" method="POST">
        @csrf

    <div>
          <label>名前</label>
    <input type="text" name="name">
    </div>

    <div>
        <label>住所</label>
        <input type="text" name="address">
    </div>

    <div>
        <label>郵便番号</label>
        <input type="text" name="postal_code">
    </div>

    <div>
        <label>電話番号</label>
        <input type="text" name="phone_number">
    </div>

    <div>
        <label>メールアドレス</label>
        <input type="email" name="email">
    </div>

    <div>
        <label>振込先</label>
        <input type="text" name="bank_account">
    </div>

    <div>
        <label>選択:</label>
        <select name="role">
            <option value="3">出品者</option>
            <option value="4">買い手</option>
        </select>
    </div>

    <div>
        <label>ID</label>
        <input type="text" name="user_id">
    </div>

    <div>
        <label>パスワード</label>
        <input type="text" name="password" >
    </div>

     <div>
        <input type="submit" value="登録">
    </div>
  
    </form>
   
</body>
</html>