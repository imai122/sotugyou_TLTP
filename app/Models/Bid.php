<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bid extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'product_id',
        'bidder_id',
        'bid_amount',
        'bid_at',
    ];

    
    protected $primaryKey = 'bid_id';

    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function yic_users(): BelongsTo
    {
        return $this->belongsTo(YIC_user::class, 'bidder_id');
    }
}
