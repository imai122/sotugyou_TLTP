<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'システム管理者ダッシュボード' }}</title>
    
    <style>
        /* =========================================
           💡 修正画面用のデザイン（強力バージョン） 
           ========================================= */
        #admin-container .admin-form {
            max-width: 600px !important; 
            margin: 0 auto !important;
        }

        /* 1行ごとの設定（確実な横並びと空間） */
        #admin-container .form-group {
            display: flex !important; 
            align-items: center !important; 
            margin-bottom: 25px !important; /* 💡 行と行の空間を少し広くしました */
            padding-bottom: 15px !important;
            border-bottom: 1px dashed #eee !important; 
        }

        /* 左側のラベル（確実な幅固定） */
        #admin-container .form-group label {
            width: 150px !important; /* 💡 ここで幅を固定してガタガタを防ぎます */
            font-weight: bold !important;
            font-size: 14px !important;
            color: #555 !important;
            flex-shrink: 0 !important; 
            margin-bottom: 0 !important;
        }

        /* 右側の入力ボックス */
        #admin-container .form-group input[type="text"],
        #admin-container .form-group input[type="email"] {
            flex-grow: 1 !important; 
            padding: 10px !important;
            border: 1px solid #ccc !important;
            border-radius: 4px !important;
            font-size: 15px !important;
            box-sizing: border-box !important;
        }

        #admin-container .form-group input:focus {
            outline: none !important;
            border-color: #007bff !important;
        }

        /* ボタンのエリア */
        #admin-container .form-actions {
            margin-top: 40px !important; /* 💡 ボタンの上の空間も広くしました */
            display: flex !important;
            gap: 15px !important;
        }
    </style>
</head>
<body>
    <div id="admin-container">
        {{ $slot }}
    </div>
</body>
</html>