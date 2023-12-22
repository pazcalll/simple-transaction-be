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

    public static function create(Product $product, int $quantity, string $reference): Transaction
    {
        if ($product->stock < $quantity) {
            throw new \Exception('Insufficient stock.', 400);
        }

        $transaction = Transaction::create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'payment_amount' => $product->price * $quantity,
            'price' => $product->price,
            'reference_no' => $reference
        ]);

        $product->stock -= $quantity;
        $product->save();

        return $transaction->makeHidden('product_id');
    }
}