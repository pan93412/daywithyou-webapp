<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderIndexResource;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrderResource;
use App\Models\Comment;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $commentsCount = Comment::where('user_id', auth()->user()->id)->count();
        $ordersCount = auth()->user()->orders()->count();

        return inertia('dashboard', [
            'commentsCount' => $commentsCount,
            'ordersCount' => $ordersCount,
        ]);
    }

    public function orders()
    {
        $orders = auth()->user()->orders()->orderByDesc('created_at')->paginate(10);

        return inertia('dashboard/orders', [
            'reply' => OrderIndexResource::collection($orders),
        ]);
    }

    public function orderDetails(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Load the order items with their related products
        $order->load('orderItems.product');

        return inertia('dashboard/orders/details', [
            'reply' => OrderResource::make($order),
            'orderItems' => OrderItemResource::collection($order->orderItems),
        ]);
    }
}
