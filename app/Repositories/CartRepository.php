<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;

class CartRepository
{
    public function getOrCreateCartForUser($userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    public function addOrUpdateItem($cartId, $productId, $quantity): CartItem
    {
        $item = CartItem::where([
            'cart_id' => $cartId,
            'product_id' => $productId,
        ])->first();

        if ($item) {
            $item->quantity = $item->quantity + $quantity;
            $item->save();
            return $item;
        }

        return CartItem::create([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => $quantity,
        ]);
    }

    public function updateItemQty($itemId, $quantity)
    {
        $item = CartItem::findOrFail($itemId);
        $item->quantity = $quantity;
        $item->save();

        return $item;
    }

    public function clearCart($cartId)
    {
        return CartItem::where('cart_id', $cartId)->delete();
    }
}
