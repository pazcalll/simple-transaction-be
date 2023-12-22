<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'payment_amount',
        'price',
        'reference_no'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeSearchByKeyword($query, ?string $keyword) {
        $query->when($keyword, function ($query) use ($keyword) {
            $query->where('reference_no', 'like', '%' . $keyword . '%');
        });
    }
}
