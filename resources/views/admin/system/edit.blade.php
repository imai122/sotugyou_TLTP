<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録情報修正</title>
</head>
<body>
    <h1>情報修正</h1>
    <form action="{{ route('admin.system.update', $user->user_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label>名前</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}">
        </div>

        <div>
            <label>住所</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}">
        </div>

        <div>
            <label>電話番号</label>
            <input type="text" name="phone_number" value="{{ old ('phone_number', $user->phone_number) }}">
        </div>

        <div>
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old ('email', $user->email) }}">
        </div>

        <button type="submit">修正する</button>

        <div>
            <a href="{{ route('admin.system.dashboard') }}"></a>
            <input type="button" value="戻る">  
        </div>
    </form>
    
</body>
</html>