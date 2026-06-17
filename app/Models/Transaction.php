<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Transaction extends Model
{
    //
    use HasFactory;


  
    
    public $incrementing = false;  
    protected $keyType = 'string';

    protected $fillable = [
        'transaction_id',
        'product_id',
        'buyer_id',
        'winnig_price', 
        'status',
        'won_at'
    ];

    public function YIC_users(): BelongsTo
    {
        return $this->belongsTo(YIC_user::class);
    }

    protected $primaryKey = 'transaction_id';

    public function products(): BelongsTo
    {
         return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
