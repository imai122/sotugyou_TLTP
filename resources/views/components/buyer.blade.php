<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>買い手ダッシュボード</title>
    
    {{-- ▼ 作成した外部CSSファイルを読み込み --}}
    <link rel="stylesheet" href="{{ asset('css/buyer.css') }}">
</head>
<body>

    <div id="buyer">
        {{ $slot }}
    </div>


    
</body>
</html>