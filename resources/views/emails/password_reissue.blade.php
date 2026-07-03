<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ID・パスワード再発行のお知らせ</title>
</head>
<body style="font-family: 'Hiragino Kaku Gothic ProN', Meiryo, sans-serif; color: #374151; line-height: 1.7;">
    <div style="max-width: 500px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #3b82f6;">ID・パスワード再発行のお知らせ</h2>

        <p>{{ $user->name }} 様</p>

        <p>ID・パスワードの再発行リクエストを受け付けました。<br>
        以下の内容で新しいパスワードを発行しましたのでご確認ください。</p>

        <div style="background-color: #f3f4f6; border-radius: 6px; padding: 20px; margin: 20px 0;">
            <p style="margin: 0 0 10px 0;">
                <strong>ID：</strong>{{ $user->user_id }}
            </p>
            <p style="margin: 0;">
                <strong>新しいパスワード：</strong>{{ $newPassword }}
            </p>
        </div>

        <p style="color: #dc2626; font-size: 0.9em;">
            ※ セキュリティのため、ログイン後は速やかにパスワードの変更をおすすめします。<br>
            ※ このメールに心当たりがない場合は、お手数ですが管理者までご連絡ください。
        </p>
    </div>
</body>
</html>
