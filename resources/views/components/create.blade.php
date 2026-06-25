<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
    
    <style>
        /* 画面全体を中央に配置する（bodyの背景色） */
        body {
            background-color: #f4f7f6;
            margin: 0;
            font-family: 'Helvetica Neue', Arial, 'Hiragino Kaku Gothic ProN', 'Hiragino Sans', Meiryo, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* 白いカード型のコンテナ本体 (#create) */
        #create {
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
        }

        /* フォーム内の見出し */
        #create h1 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }

        /* 各入力項目のまとまり（divで囲まれた部分） */
        #create div {
            margin-bottom: 20px;
        }

        /* ラベル（名前、住所などのテキスト） */
        #create label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
        }

        /* 入力欄（テキスト、メール、パスワード、セレクトボックス） */
        #create input[type="text"],
        #create input[type="email"],
        #create input[type="password"],
        #create select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        /* 入力欄を選択したとき（フォーカス時）の青い枠 */
        #create input:focus,
        #create select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        }

        /* 登録・送信ボタン */
        #create input[type="submit"],
        #create button {
            width: 100%;
            padding: 14px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        /* ボタンにカーソルを乗せたとき */
        #create input[type="submit"]:hover,
        #create button:hover {
            background-color: #0056b3;
        }

        /* エラーメッセージ（もし使う場合） */
        #create .error-message {
            color: #dc3545;
            font-size: 0.85em;
            margin-top: 5px;
        }

        /* 必須マーク（赤い※） */
        #create .required {
            color: #e60000; /* 赤色 */
            font-size: 0.9em; /* 少しだけ小さく */
            margin-left: 5px; /* 左に少し隙間を空ける */
        }
    </style>
</head>
<body>

    <div id="create">
        {{ $slot }}
    </div>

</body>
</html>