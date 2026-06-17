<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
class Product extends Model
{
    //
    use HasFactory;

    protected $primaryKey = 'product_id';
    public $incrementing = true; 
    // protected $keyType = 'string'; // IDの型が文字列であることを明示

    protected $fillable = ['product_name', 'comment'];

    public function productId(): Attribute
    {
        return Attribute::make(
            get:fn(string $value) => 'S' . str_pad($value, 3, '0', STR_PAD_LEFT),
        );
    }

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function yic_users(): BelongsTo
    {
       return $this->belongsTo(YIC_user::class, 'seller_id', 'user_id');
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'product_id', 'product_id');
    }

//     protected static function boot()
//     {
//         parent::boot();
        
//         static::creating(function ($product) {
//             // product_id が空なら、UUID（ユニークな文字列）を生成してセットする
//             if (empty($product->product_id)) {
//                 $product->product_id = (string) Str::uuid();
//             }
//         });
//     }
}
