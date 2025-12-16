<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\HandleJsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    use HandleJsonResponse;

    public function index()
    {
        return Order::with('user')->paginate(10);
    }

    public function approve(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::where('id', $request->order_id)->first();

        if ($order->status !== 'pending') {
            return $this->error(['error' => 'Order already processed'], 400);
        }

        $order->update([
            'status' => 'shipped'
        ]);

        return $this->success([], 'Approved Successfully');
    }
}
