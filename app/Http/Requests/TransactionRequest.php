<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $product = Product::where('user_id', auth()->user()->id)
            ->where('id', request()->product_id)
            ->exists();

        if (!$product) throw new \Exception('Product not found.', 404);

        return [
            //
            'quantity' => ['required', 'integer', 'min:1'],
            'product_id' => ['required', 'exists:products,id']
        ];
    }
}
