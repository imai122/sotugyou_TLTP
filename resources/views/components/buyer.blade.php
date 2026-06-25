<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>買い手ダッシュボード</title>

<style>
        
        .buyer-container {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* アラートメッセージ */
        .alert {
            padding: 15px; 
            margin-bottom: 20px; 
            border-radius: 6px; 
            font-weight: bold;
        }

        .alert-error { 
            color: #721c24;
             background-color: #f8d7da; 
             border: 1px solid #f5c6cb;
        }

        .alert-success { 
            color: #155724;
             background-color: #d4edda;
              border: 1px solid #c3e6cb;
     }

        /* タブナビゲーション */
        .tab-nav {
            display: flex; 
            gap: 10px;
            align-items: center;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 20px; 
            padding-bottom: 10px;
        }

        .tab-button {
            background: none; 
            border: none; padding: 10px 20px; 
            font-size: 16px;
            cursor: pointer; color: #64748b; 
            font-weight: bold; 
            border-radius: 6px;
            transition: all 0.2s;
        }

        .tab-button:hover { 
            background-color: #f1f5f9; 
            color: #334155; 
        }

        .tab-button.active { 
            background-color: #3b82f6; 
            color: white;
         }
        
        .logout-link {
            margin-left: auto; 
            color: #ef4444; 
            font-weight: bold; 
            text-decoration: none; 
            padding: 10px;
        }

        .logout-link:hover { 
            text-decoration: underline; 
        }

        /* タブの中身 */
        .tab-content { 
            display: none; 
            background: #fff; 
            padding: 25px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
        }

        .tab-content h2 { 
            margin-top: 0; 
            padding-left: 10px; 
            border-left: 4px solid #3b82f6; 
            margin-bottom: 20px; 
        }

        /* 検索ボックス */
        .search-box { 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            margin-bottom: 20px; 
        }

        .search-box input[type="text"] { 
            padding: 8px 12px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            width: 250px; 
        }

        .search-box input[type="submit"] { 
            padding: 8px 20px; 
            background: #6c757d; 
            color: white; 
            border: none; border-radius: 4px; 
            cursor: pointer; 
            transition: 0.2s;
         }

        .search-box input[type="submit"]:hover { 
            background: #5a6268; 
        }

        /* 商品カード（閲覧タブ） */
        .product-grid { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 20px;
         }

        .product-card { 
            display: flex; 
            flex-direction: column; 
            gap: 8px; /* 上のカードと入札ボタンの間の隙間 */
            width: max-content; /* 中のコンポーネントの幅にピッタリ合わせる */
        }
        .product-card:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
       .btn-bid { 
            display: block; 
            padding: 12px; 
            background-color: #007bff; 
            color: white; 
            text-align: center;
            text-decoration: none; 
            border-radius: 6px; 
            font-weight: bold; 
            transition: 0.2s; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-bid:hover { 
            background-color: #0056b3;
         }

        /* リスト行（履歴タブ） */
        .list-header { 
            display: flex; 
            font-weight: bold; 
            background: #f8fafc; padding: 12px 15px; 
            border-bottom: 2px solid #cbd5e1; 
            border-radius: 6px 6px 0 0;
         }

        .list-row { 
            display: flex; 
            padding: 15px; 
            border-bottom: 1px solid #e2e8f0; 
            align-items: center; 
            transition: background 0.2s; 
        }

        .list-row:hover { 
            background: #f8fafc;
        }

        .col-150 { 
            width: 150px; 
            padding-right: 10px; 
            word-break: break-all; 
        }

        .col-180 { 
            width: 180px; 
        }
        
        /* 通知カード */
        .notification-card { 
            border: 1px solid #e2e8f0; 
            border-radius: 8px; padding: 20px; 
            margin-bottom: 15px; 
            background: #ffffff; 
        }

        .btn-action { 
            display: inline-block; 
            padding: 10px 20px; 
            color: white; 
            border-radius: 6px; 
            text-decoration: none; 
            font-weight: bold; 
            transition: 0.2s; 
        }

        .btn-action:hover { 
            opacity: 0.8; 
        }

        .btn-green { 
            background: #10b981; 
        }

        .btn-blue { 
            background: #3b82f6; 
        }

        .status-msg-warning { 
            padding: 12px; 
            background-color: #fffbeb; color: #b45309; 
            border: 1px solid #fef3c7; font-weight: bold; 
            margin-bottom: 15px; 
            border-radius: 6px; 
        }

        .status-msg-success { 
            padding: 12px; 
            background-color: #f0fdf4; color: #166534; 
            border: 1px solid #dcfce3; 
            font-weight: bold; border-radius: 6px; 
            display: inline-block; 
        }

        /* バッジ */
        .badge-count { 
            font-size: 0.95rem; 
            font-weight: bold; color: #4b5563; 
            background-color: #f3f4f6; padding: 8px 16px; 
            border-radius: 20px; 
            border: 1px solid #d1d5db; 
        }

        .badge-count span { 
            color: #10b981; font-size: 1.2rem; 
            margin: 0 4px;
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

<div id="buyer">
        {{ $slot }}
</div>

</body>
</html>
    