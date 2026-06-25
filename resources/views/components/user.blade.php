<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>出品者ダッシュボード</title>
    
    {{-- 💡 全画面共通の洗練されたCSS --}}
    <style>
        /* 1. 全体のベース（上品なグレー背景と見やすいフォント） */
        body { 
            font-family: 'Helvetica Neue', Arial, 'Hiragino Kaku Gothic ProN', 'Hiragino Sans', Meiryo, sans-serif; 
            background-color: #f3f4f6; 
            color: #374151; 
            margin: 0; 
            padding: 40px 20px; 
        }

        /* 画面が広すぎても間延びしないように中央に寄せる */
        #main-content {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        /* 2. タブのスタイル（最近のアプリ風の下線デザイン） */
        .tab-button { 
            display: inline-block; 
            cursor: pointer; 
            padding: 12px 24px; 
            background: transparent; 
            color: #6b7280;
            border: none; 
            border-bottom: 3px solid transparent; /* アクティブ時に下線を出す準備 */
            font-size: 1rem;
            font-weight: bold;
            transition: all 0.3s ease; /* ふわっと色が変わるアニメーション */
        }
        .tab-button:hover { 
            color: #3b82f6; 
            background-color: #eff6ff;
            border-radius: 6px 6px 0 0;
        }
        .tab-button.active { 
            color: #3b82f6;
            border-bottom: 3px solid #3b82f6; /* 青い太線で現在地を強調 */
        }
        
        /* 3. コンテンツエリア（カードのように浮かせる） */
        .tab-content { 
            display: none; 
            padding: 30px; 
            background: #ffffff; 
            border-radius: 8px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* 立体的な影 */
        }
        
        /* 4. フォームのスタイル（入力しやすさを向上） */
        .product-form { max-width: 600px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: bold; 
            color: #4b5563;
            font-size: 0.95rem;
        }
        .form-group input[type="text"], 
        .form-group input[type="number"], 
        .form-group select, 
        .form-group textarea { 
            width: 100%; 
            padding: 10px 12px; 
            border: 1px solid #d1d5db; 
            border-radius: 6px; 
            box-sizing: border-box; 
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        /* 入力欄をクリックした時に青く光るエフェクト */
        .form-group input:focus, 
        .form-group select:focus, 
        .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .form-group textarea { height: 120px; resize: vertical; }
        
        /* 5. ボタン（押したくなるような立体感） */
        .submit-btn { 
            padding: 12px 24px; 
            background-color: #3b82f6; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 1rem;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
            transition: background-color 0.3s, transform 0.1s;
        }
        .submit-btn:hover { 
            background-color: #2563eb; 
            transform: translateY(-2px); /* マウスを乗せると少し上に浮く */
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.4);
        }
        
        /* 6. メッセージ（より親しみやすい色合いに） */
        .alert-success { 
            color: #065f46; background-color: #d1fae5; 
            padding: 16px; 
            margin-bottom: 20px; 
            border-radius: 6px; 
            border: 1px solid #a7f3d0; 
            font-weight: bold; 
        }

        .alert-error { 
            color: #991b1b; 
            background-color: #fee2e2; 
            padding: 16px; 
            margin-bottom: 20px; 
            border-radius: 6px; 
            border: 1px solid #fecaca; 
            font-weight: bold; 
        }

        /* 7. 通知バッジ（エラーを修正し、綺麗な丸みに） */
        .notification-badge {
            background-color: #ef4444; 
            color: white;
            border-radius: 9999px; /* 完全な丸 */
            padding: 2px 8px;
            font-size: 0.75rem;
            font-weight: bold;
            margin-left: 8px;
            vertical-align: middle;
            display: inline-block;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        
        .notification-card {
          border: 1px solid #e2e8f0; /* 薄いグレーの枠線 */
          border-radius: 12px;       /* 角を丸くして優しく */
          padding: 20px;
          margin-bottom: 15px;
          background-color: #ffffff;
          box-shadow: 0 2px 4px rgba(0,0,0,0.05); /* 影をつけて浮かせる */
          transition: transform 0.2s;
        }

        .notification-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* 乗せると少し浮き出る */
}

       
    </style>
</head>
<body>

    <div id="main-content">
        {{ $slot }}
    </div>

</body>
</html>