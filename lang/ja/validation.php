<?php
return [
    'required' => ':attributeは必須項目です。',
    'exists' => '選択された:attributeは正しくありません。',
    'unique' => ':attributeは既に使用されています。',
    'confirmed' => ':attributeが一致しません。',
    'integer' => ':attributeは整数で入力してください。',
    'date' => ':attributeは正しい日付形式で入力してください。',
    'after' => ':attributeは現在より後の日時を指定してください。',
    'image' => ':attributeは画像ファイルを選択してください。',
    'mimes' => ':attributeは:values形式のファイルを選択してください。',
    'in' => '選択された:attributeは正しくありません。',
    'string' => ':attributeは文字列で入力してください。',

    'max' => [
        'numeric' => ' :attributeは :max以下で入力してください',
        'string'  => ':attributeは:max文字以内で入力してください。',
        'file'    => ':attributeは:max KB以下のファイルを選択してください。',
    ],

    'min' => [
        'string' => ':attributeは :min文字以上で入力してください。',
        'numeric' => ':attributeは:min以上で入力してください。',
        'integer' => ':attributeは:min以上で入力してください。',
    ],

    'unique' => ':attributeは既に使用されています。',
    'confirmed' => ':attributeが一致しません。',

     'attributes' => [
        'user_id' => 'ID',
        'password' => 'パスワード',
        'name' => '名前',
        'postal_code' => '郵便番号',
        'phone_number' => '電話番号',
        'email' => 'メールアドレス',
        'bank_account' => '銀行口座',
        'address' => '住所',
        'role' => '区分',
        'category_id' => 'カテゴリ',
        'product_name' => '商品名',
        'image_path' => '写真',
        'comment' => '商品説明',
        'wish_price' => '希望価格',
        'end_date' => '落札期限',
        'bid_amount' => '入札金額',
    ],

    'custom' => [
        'password' =>[
            'regex' => ':attributeは大文字で始まるためそれを含めて入力してください。',
        ],
        'user_id' => [
            'exists' => '入力されたIDは登録されていません。',
        ],
    ],
];