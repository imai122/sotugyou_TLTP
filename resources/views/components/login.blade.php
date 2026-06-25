<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
        <style>
            /* 画面全体を中央寄せにするラッパー */
            .login-wrapper {
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                background-color: #f3f4f6; /* 薄いグレーの背景 */
                padding: 20px;
                font-family: 'Helvetica Neue', Arial, 'Hiragino Kaku Gothic ProN', 'Hiragino Sans', Meiryo, sans-serif;
            }

            /* ログインフォームのカード型デザイン */
            .login-card {
                background: #ffffff;
                width: 100%;
                max-width: 400px;
                padding: 2rem;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .login-card h1 {
                text-align: center;
                font-size: 1.5rem;
                color: #333333;
                margin-top: 0;
                margin-bottom: 1.5rem;
            }

            /* 入力フォームのグループ */
            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-group label {
                display: block;
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
                color: #4b5563;
                font-weight: bold;
            }

            .form-group input[type="text"],
            .form-group input[type="password"] {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid #d1d5db;
                border-radius: 4px;
                font-size: 1rem;
                box-sizing: border-box;
                transition: border-color 0.3s;
            }

            .form-group input[type="text"]:focus,
            .form-group input[type="password"]:focus {
                outline: none;
                border-color: #3b82f6; /* フォーカス時に青枠 */
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            }

            /* 送信ボタン */
            .submit-btn {
                width: 100%;
                padding: 0.75rem;
                background-color: #3b82f6;
                color: #ffffff;
                border: none;
                border-radius: 4px;
                font-size: 1rem;
                font-weight: bold;
                cursor: pointer;
                transition: background-color 0.3s;
                margin-bottom: 1.5rem;
            }

            .submit-btn:hover {
                background-color: #2563eb;
            }

            /* サブボタンのレイアウト */
            .action-links {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .action-links a {
                text-decoration: none;
                width: 100%;
            }

            .action-btn {
                width: 100%;
                padding: 0.6rem;
                background-color: #f3f4f6;
                color: #374151;
                border: 1px solid #d1d5db;
                border-radius: 4px;
                font-size: 0.85rem;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            .action-btn:hover {
                background-color: #e5e7eb;
            }
        </style>
        </head>
        <body>

    <div id="main-content">
        {{ $slot }}
    </div>

</body>
</html>