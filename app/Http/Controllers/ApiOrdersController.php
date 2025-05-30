<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderIndexResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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

    public function store(Request $request)
    {
        $order = $request->validate([
            'recipient_name' => ['required', 'string'],
            'recipient_email' => ['required', 'email'],
            'recipient_phone' => ['required', 'string', 'min:2'],
            'recipient_address' => ['required', 'string', 'min:2'],
            'recipient_city' => ['required', 'string', 'min:2'],
            'recipient_zip_code' => ['required', 'string', 'min:3'],
            'note' => ['nullable', 'string'],
            'payment_method' => ['required', 'string', Rule::in(['cash', 'line_pay', 'bank_transfer'])],
        ]);

        $orderItems = $request->validate([
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', Rule::exists('products', 'id')],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $orderModel = DB::transaction(function () use ($order, $orderItems) {
            $orderModel = auth()->user()->orders()->create($order);

            foreach ($orderItems['products'] as $orderItem) {
                $orderModel->orderItems()->create($orderItem);
            }

            return $orderModel;
        });

        return OrderResource::make($orderModel);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response(null, 204);
    }
}
