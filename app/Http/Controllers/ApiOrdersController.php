<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderIndexResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

#[Group('訂單管理')]
class ApiOrdersController extends Controller
{
    /**
     * 取得訂單列表
     *
     * 回傳已驗證使用者的訂單列表，以分頁方式呈現。
     */
    public function index(Request $request)
    {
        /**
         * 每頁顯示的項目數量
         */
        $perPage = $request->integer('per_page', 10);
        /**
         * 目前頁數
         */
        $page = $request->integer('page', 1);

        // show only the orders of the authenticated user
        $orders = Order::where('user_id', auth()->user()->id)
            ->paginate($perPage, page: $page);

        return OrderIndexResource::collection($orders);
    }

    /**
     * 取得特定訂單
     *
     * 根據 ID 取得特定訂單的詳細資訊，包含訂單項目及相關產品。
     *
     * @throws AccessDeniedHttpException 當訂單不屬於已驗證使用者時
     */
    public function show(Order $order)
    {
        // check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->user()->id) {
            throw new AccessDeniedHttpException('You do not have permission to view this order.');
        }

        $order->load('orderItems.product');

        return OrderResource::make($order);
    }

    /**
     * 建立新訂單
     *
     * 建立一個新的訂單，包含收件人資訊、付款方式及訂購產品。
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            /**
             * 收件人姓名
             */
            'recipient_name' => ['required', 'string'],
            /**
             * 收件人電子信箱
             */
            'recipient_email' => ['required', 'email'],
            /**
             * 收件人電話
             */
            'recipient_phone' => ['required', 'string', 'min:2'],
            /**
             * 收件人地址
             */
            'recipient_address' => ['required', 'string', 'min:2'],
            /**
             * 收件人城市
             */
            'recipient_city' => ['required', 'string', 'min:2'],
            /**
             * 收件人郵遞區號
             */
            'recipient_zip_code' => ['required', 'string', 'min:3'],
            /**
             * 訂單備註
             */
            'note' => ['nullable', 'string'],
            /**
             * 付款方式
             */
            'payment_method' => ['required', 'string', Rule::in(['cash', 'line_pay', 'bank_transfer'])],
            /**
             * 訂購產品列表
             */
            'products' => ['required', 'array', 'min:1'],
            /**
             * 產品 ID
             */
            'products.*.product_id' => ['required', Rule::exists('products', 'id')],
            /**
             * 產品數量
             */
            'products.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $orderModel = DB::transaction(function () use ($input) {
            $orderModel = auth()->user()->orders()->create($input);

            foreach ($input['products'] as $orderItem) {
                $orderModel->orderItems()->create($orderItem);
            }

            return $orderModel;
        });

        return OrderResource::make($orderModel);
    }

    /**
     * 刪除訂單
     *
     * 刪除指定的訂單。
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return response(null, 204);
    }
}
