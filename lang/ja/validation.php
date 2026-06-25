<?php
return [
    'max' => [
        'numeric' => ' :attributeは :max以下で入力してください',
    ],
    'required' => ':attributeは必須項目です。',

    'min' => [
        'string' => ':attributeは :min文字以上で入力してください。',
    ],

    'attributes' => [
        'user_id' => 'ID',
        'password' => 'パスワード'
    ],

    'custom' => [
        'password' =>[
            'regex' => ':attributeは大文字で始まるためそれを含めて入力してください。',
        ],
    ],
];