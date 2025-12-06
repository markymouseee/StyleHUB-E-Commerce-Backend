<?php

namespace App\Services;

use App\Repositories\CartRepository;
use App\Repositories\Interfaces\ProductRepository;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\DB;

class CartService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected readonly CartRepository $cartRepository, protected readonly ProductRepository $productRepository) {}

    public function addToCart($userId, $productId, $qty = 1)
    {
        $cart = $this->cartRepository->getOrCreateCartForUser($userId);
        $product = $this->productRepository->find($productId);

        if ($product->stock < $qty) {
            throw new ValidationException('Not enough stock for product: ' . $product->name);
        }

        return $this->cartRepository->addOrUpdateItem($cart->id, $productId, $qty);
    }

    public function viewCart($userId)
    {
        $cart = $this->cartRepository->getOrCreateCartForUser($userId);
        return $cart->load('items.product');
    }

    public function checkout($userId, $address)
    {
        $cart = $this->cartRepository->getOrCreateCartForUser($userId);
        $items = $cart->cartItems()->with('product')->get();

        if ($items->isEmpty()) {
            throw new ValidationException('Cart is empty.');
        }

        $total = 0;

        foreach ($items as $item) {
            if ($item->product->stock < $items->quantity) {
                throw new ValidationException('Not enough stock for product: ' . $item->product->name);
            }

            $total += $item->product->price * $item->quantity;
        }

        DB::beginTransaction();
        try {
            $order = \App\Models\Order::create([
                'user_id' => $userId,
                'total_amount' => $total,
                'shipping_address' => $address,
                'status' => 'pending',
            ]);

            foreach ($items as $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price_at_order' => $item->product->price,
                    'subtotal' => $item->product->price * $item->quantity,
                ]);

                $product = $item->product;
                $product->stock = max(0, $product->stock - $item->quantity);
                $product->save();
            }

            $this->cartRepository->clearCart($cart->id);
            DB::commit();

            return $order->load('items.product');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
