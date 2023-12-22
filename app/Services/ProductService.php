<?php

namespace App\Services;

use App\Models\Product;

class ProductService {
    public static function index(int $userId, ?string $keyword) {
        $product = Product::where('user_id', $userId)
            ->searchByKeyword($keyword)
            ->paginate();

        return $product;
    }
}