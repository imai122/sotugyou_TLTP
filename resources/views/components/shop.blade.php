<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'ショップ管理者ダッシュボード' }}</title>
    
    <style>
        /* 画面全体の背景とフォント */
        body {
            font-family: 'Helvetica Neue', Arial, 'Hiragino Kaku Gothic ProN', 'Hiragino Sans', Meiryo, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 30px;
        }

        /* メインコンテナ */
        #admin-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 { 
            margin-top: 0; 
            font-size: 24px; color: #333; 
            margin-bottom: 25px; 
        }

        h2 { 
            font-size: 18px; color: #555; 
            margin-top: 0; 
            margin-bottom: 15px; 
        }

        /* 💡 タブボタンのデザイン */
        .tab-navigation {
            border-bottom: 2px solid #ddd;
            margin-bottom: 25px;
            display: flex;
            gap: 5px;
        }
        .tab-button {
            background-color: #e9ecef;
            color: #495057;
            border: 1px solid #ddd;
            border-bottom: none;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: -2px;
        }
        .tab-button:hover { 
            background-color: #dee2e6; 
        }

        .tab-button.active {
            background-color: #fff;
            color: #007bff;
            border-color: #ddd;
            border-bottom: 2px solid #fff;
            position: relative;
            z-index: 2;
        }

        /* タブコンテンツの初期状態 */
        .tab-content { display: none; }

        /* 検索ボックス */
        .search-box {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #eaeaea;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .search-box label { 
            font-weight: bold; 
            font-size: 14px; 
        }

    
        .search-box input[type="text"] { 
            padding: 8px 12px; 
            width: 250px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            font-size: 14px; 
        }

        .search-box input[type="button"], 
        .search-box input[type="submit"] { 
            padding: 8px 20px; 
            background-color: #6c757d; 
            color: white; border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 14px; 
        }

        .search-box input[type="button"]:hover, 
        .search-box input[type="submit"]:hover { 
            background-color: #5a6268; 
        }

        /* テーブル（表）のデザイン */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: #fff;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            text-align: center;
            vertical-align: middle;
            font-size: 14px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #555;
            border-bottom: 2px solid #ddd;
        }
        tr:hover { background-color: #fcfcfc; }
        
        /* 💡 特定のステータス行（入金済みなど）のハイライト背景 */
        .bg-highlight { 
            background-color: #fffdeb !important; 
        }

        .bg-highlight:hover { 
            background-color: #fffbcf !important; 
        }

        /* ボタン類の共通スタイル */
        .btn-edit { 
            display: inline-block; 
            background-color: #ffc107; 
            color: #212529; 
            padding: 6px 15px; 
            border-radius: 4px; 
            text-decoration: none; 
            font-size: 13px; 
            font-weight: bold; 
            margin-right: 5px; 
            text-align: center; 
        }

        .btn-delete { 
            display: inline-block; 
            background-color: #333; 
            color: white; border: none; 
            padding: 6px 15px; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 13px; 
            font-weight: bold; 
        }

        .btn-action { 
            display: inline-block; 
            background-color: #28a745; 
            color: white; padding: 8px 16px; 
            border-radius: 4px; 
            text-decoration: none; 
            font-size: 13px; 
            font-weight: bold; 
            margin-top: 5px; 
            text-align: center; 
        }

        .btn-edit:hover, .btn-delete:hover, .btn-action:hover { 
            opacity: 0.8; 
        }

        /* 強調テキスト */
        .text-danger { 
            color: #dc3545; 
            font-weight: bold; 
        }

        .text-muted { 
            color: #6c757d; 
        }
        
    </style>
</head>
<body>
    <div id="admin-container">
        {{ $slot }}
    </div>
</body>
</html>