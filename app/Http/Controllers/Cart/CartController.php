<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;

use App\Models\CartItem;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected readonly CartService $cartService, protected readonly CartRepository $cartRepository) {}

    public function index()
    {
        return CartItem::count();
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'integer|min:1'
        ]);

        $item = $this->cartService->addToCart($validate['user_id'], $validate['product_id'], $validate['quantity']);

        return response()->json($item);
    }

    public function show($id)
    {
        return $this->cartService->viewCart($id);
    }

    public function destroy($id)
    {
        $this->cartRepository->clearCart($id);
    }
}
