<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderConfirmationResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class OrdersController extends Controller
{
    /**
     * Display the order confirmation page.
     */
    public function confirmation()
    {
        // get order ID from the session
        $orderId = session('order');
        Log::info("confirmation orderId: $orderId");
        if (! $orderId || ! \is_int($orderId)) {
            return to_route('carts.index')->with([
                'error' => '您尚未執行下單動作。如果下單失敗，請重新操作。',
            ]);
        }

        $order = Order::find($orderId);

        return Inertia::render('orders/confirmation', [
            'reply' => OrderConfirmationResource::make($order),
        ]);
    }

    /**
     * Create a new order according to the cart.
     */
    public function store(CartService $cartService, Request $request)
    {
        $cartItems = $cartService->list();

        if (\count($cartItems) === 0) {
            return to_route('carts.index')->with([
                'error' => '購物車是空的。',
            ]);
        }

        $payload = $request->validate([
            'recipient_name' => ['required', 'string'],
            'recipient_email' => ['required', 'email'],
            'recipient_phone' => ['required', 'string', 'min:2'],
            'recipient_address' => ['required', 'string', 'min:2'],
            'recipient_city' => ['required', 'string', 'min:2'],
            'recipient_zip_code' => ['required', 'string', 'min:3'],
            'note' => ['nullable', 'string'],
            'payment_method' => ['required', 'string', Rule::in(['cash', 'line_pay', 'bank_transfer'])],
        ]);

        // create transaction
        try {
            $orderId = DB::transaction(function () use ($payload, $cartItems) {
                $order = Order::create([
                    ...$payload,
                    'user_id' => auth()->user()->id,
                ]);

                foreach ($cartItems as $productId => $cartItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'quantity' => $cartItem->quantity,
                    ]);
                }

                return $order->id;
            }, attempts: 3);
        } catch (\Throwable $e) {
            Log::error('Failed to create order: '.$e);

            return to_route('carts.index')->with([
                'error' => $e->getMessage(),
            ]);
        }

        // clear cart since the order has been created
        $cartService->clear();

        // redirect to confirmation page
        return to_route('orders.confirmation')->with([
            'order' => $orderId,
        ]);
    }
}
