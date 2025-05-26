<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderConfirmationResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\State\MessageState;
use App\Models\State\MessageStateType;
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
        if (! $orderId || ! \is_int($orderId)) {
            return to_route('carts.index')->with([
                MessageState::$MESSAGE_SESSION_KEY => (new MessageState(
                    type: MessageStateType::ERROR,
                    title: '動作有誤',
                    content: '您尚未執行下單動作。如果下單失敗，請重新操作。',
                ))->toArray(),
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
                MessageState::$MESSAGE_SESSION_KEY => (new MessageState(
                    type: MessageStateType::ERROR,
                    title: '購物車是空的',
                    content: '請先加入商品至購物車。',
                ))->toArray(),
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
                MessageState::$MESSAGE_SESSION_KEY => (new MessageState(
                    type: MessageStateType::ERROR,
                    title: '下單失敗',
                    content: '請稍後再試。錯誤訊息：'.$e->getMessage(),
                ))->toArray(),
            ]);
        }

        // clear cart since the order has been created
        $cartService->clear();

        // redirect to confirmation page
        return to_route('orders.confirmation')->with([
            'order' => $orderId,
        ]);
    }

    public function cancel(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            return back()->with([
                MessageState::$MESSAGE_SESSION_KEY => (new MessageState(
                    type: MessageStateType::ERROR,
                    title: '權限不足',
                    content: '您沒有權限取消此訂單。',
                ))->toArray(),
            ]);
        }

        try {
            DB::beginTransaction();

            // Log the cancellation
            Log::info("Cancelling order ID $order->id");

            // Delete the order (this will cascade delete order items due to foreign key constraints)
            $order->delete();

            DB::commit();

            return to_route('dashboard.orders')->with([
                MessageState::$MESSAGE_SESSION_KEY => (new MessageState(
                    type: MessageStateType::DEFAULT,
                    title: '取消訂單成功',
                    content: "訂單 #{$order->id} 已成功取消。",
                ))->toArray(),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Order cancellation failed: '.$e->getMessage());

            return back()->with([
                MessageState::$MESSAGE_SESSION_KEY => (new MessageState(
                    type: MessageStateType::ERROR,
                    title: '取消訂單失敗',
                    content: '請稍後再試。錯誤訊息：'.$e->getMessage(),
                ))->toArray(),
            ]);
        }
    }
}
