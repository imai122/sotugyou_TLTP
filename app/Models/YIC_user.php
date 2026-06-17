<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;//ログイン実装

class YIC_user extends Authenticatable//メール処理実装
{
    //
    use HasFactory,Notifiable;

    

    protected $fillable = [
    'name', 'address', 'postal_code', 'phone_number', 'email', 'bank_account', 'user_id', 'password', 'role'
];

    protected $table = 'yic_users';

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function products(): HasMany{
        return $this->hasMany(Product::class);
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
