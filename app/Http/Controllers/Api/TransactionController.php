<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Product;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TransactionController extends Controller
{
    // public function __construct() {
    //     $this->middleware('transaction.header')->only(['store']);
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $transactions = TransactionService::index(auth()->user()->id, request()->keyword);

        return $transactions;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {
        //
        $cache = Cache::lock('transaction', 5)->block(5, function () use ($request) {
            $validated = $request->validated();
            $product = Product::find($validated['product_id']);

            $transaction = TransactionService::create($product, $validated['quantity']);

            return responseJson($transaction);
        });

        return $cache;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
