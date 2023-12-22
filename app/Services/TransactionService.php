<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;

class TransactionService
{
    public static function index(int $userId, ?string $keyword) {
        $transactions = Transaction::whereHas('product', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->searchByKeyword($keyword)
            ->paginate();

        return $transactions;
    }

    public static function create(Product $product, int $quantity): Transaction
    {
        $transaction = Transaction::create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'payment_amount' => $product->price * $quantity,
            'price' => $product->price,
            'reference_no' => 'INV'.request()->header('X-SIGNATURE')
        ]);

        return $transaction->makeHidden('product_id');
    }
}