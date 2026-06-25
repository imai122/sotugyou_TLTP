<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'システム管理者ダッシュボード' }}</title>
    
    <style>
        /* 画面全体の背景とフォント */
        body {
            font-family: 'Helvetica Neue', Arial, 'Hiragino Kaku Gothic ProN', 'Hiragino Sans', Meiryo, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 30px;
        }

        /* ダッシュボードのメインコンテナ */
        #admin-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            max-width: 1200px;
            margin: 0 auto;
        }

        /* 見出し */
        #admin-container h1 {
            margin-top: 0;
            font-size: 22px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
            color: #444;
        }

        /* 検索バーのエリア */
        #admin-container .search-box {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #eaeaea;
        }

        #admin-container .search-box label {
            font-weight: bold;
            font-size: 14px;
            margin-right: 10px;
        }

        #admin-container .search-box input[type="text"] {
            padding: 8px 12px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            margin-right: 10px;
        }

        #admin-container .search-box input[type="submit"] {
            padding: 8px 20px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        #admin-container .search-box input[type="submit"]:hover {
            background-color: #5a6268;
        }

        #admin-container .search-box a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
            margin-left: 15px;
        }

        #admin-container .search-box a:hover {
            text-decoration: underline;
        }

        /* テーブル（表）のデザイン */
        #admin-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: #fff;
        }

        #admin-container th, 
        #admin-container td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            text-align: center;
            vertical-align: middle;
            font-size: 14px;
        }

        #admin-container th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #555;
            border-bottom: 2px solid #ddd;
        }

        #admin-container tr:hover {
            background-color: #fcfcfc;
        }

        /* 操作ボタン */
        #admin-container .btn-edit {
            display: inline-block;
            background-color: #ffc107;
            color: #212529;
            padding: 6px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            margin-right: 5px;
            transition: opacity 0.2s;
        }

        #admin-container .btn-delete {
            display: inline-block;
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            transition: opacity 0.2s;
        }

        #admin-container .btn-edit:hover,
        #admin-container .btn-delete:hover {
            opacity: 0.8;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;     /* 内側の余白（上下5px、左右12px） */
            border-radius: 20px;   /* 角を丸くする（数字を大きくするとより丸くなります） */
            font-size: 12px;       /* 文字の大きさ */
            color: white;          /* 文字の色を白にする */
            font-weight: bold;     /* 文字を太くする */
            text-align: center;
        }

        /* 権限ごとの背景色 */
        .badge-shop { 
            background-color: #dc3545; 
        } /* ショップ管理者は赤 */

        .badge-seller { 
            background-color: #28a745; 
        } /* 出品者は緑 */

        .badge-buyer { 
            background-color: #007bff; 
        }  /* 買い手は青 */
        
    </style>
</head>
<body>
    <div id="admin-container">
        {{ $slot }}
    </div>
</body>
</html>