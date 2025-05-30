<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;

#[Group('產品目錄')]
class ApiProductsController extends Controller
{
    /**
     * 取得產品列表
     *
     * 回傳分頁的產品列表。
     *
     * @unauthenticated
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

        $products = Product::paginate($perPage, page: $page);

        return ProductIndexResource::collection($products);
    }

    /**
     * 取得特定產品
     *
     * 根據 `slug` 取得特定產品的詳細資訊。
     *
     * @unauthenticated
     */
    public function show(Product $product)
    {
        return ProductResource::make($product);
    }
}
