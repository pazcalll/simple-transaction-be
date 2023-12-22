<?php

namespace App\Services;

use App\Models\Product;

class ProductService {
    public static function index(int $userId, ?string $keyword, ?int $perPage = 10) {
        $product = Product::where('user_id', $userId)
            ->searchByKeyword($keyword)
            ->paginate($perPage);

        return $product;
    }
}