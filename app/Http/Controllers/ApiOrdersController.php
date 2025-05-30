<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderIndexResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ApiOrdersController extends Controller
{
    public function index()
    {
        // show only the orders of the authenticated user
        $orders = Order::where('user_id', auth()->user()->id);

        return OrderIndexResource::collection($orders->get());
    }

    public function show(Order $order)
    {
        // check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->user()->id) {
            throw new AccessDeniedHttpException('You do not have permission to view this order.');
        }

        $order->load('orderItems.product');

        return OrderResource::make($order);
    }
}
