<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;

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
        if ($product->stock < $quantity) {
            throw new \Exception('Insufficient stock.', 400);
        }

        $response = Http::withHeaders([
            'X-API-KEY' => 'DATAUTAMA',
            'X-SIGNATURE' => hash('sha256', request()->method().':'.'DATAUTAMA' )
        ])->post('http://tes-skill.datautama.com/test-skill/api/v1/transactions', [
            "quantity" => $quantity,
            "price" => $product->price,
            "payment_amount" => $product->price * $quantity
        ])
        ->json();

        $transaction = Transaction::create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'payment_amount' => $product->price * $quantity,
            'price' => $product->price,
            'reference_no' => $response['data']['reference_no'],
        ]);

        $product->stock -= $quantity;
        $product->save();

        return $transaction->makeHidden('product_id');
    }
}