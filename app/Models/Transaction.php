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
        'won_at',
        'payment_received_at', 
        'delivered_at',        
        'payout_amount',       
        'payout_completed_at', 
    ];

    public function YIC_users(): BelongsTo
    {
        return $this->belongsTo(YIC_user::class, 'buyer_id', 'user_id');
    }

    protected $primaryKey = 'transaction_id';

    public function products(): BelongsTo
    {
         return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
