<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected readonly CartService $cartService) {}

    public function index() {}

    public function store(Request $request)
    {
        $validate = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'integer|min:1'
        ]);

        $userId = $request->user()->id;

        $item = $this->cartService->addToCart($userId, $validate['product_id'], $validate['quantity']);

        return response()->json($item);
    }
}
