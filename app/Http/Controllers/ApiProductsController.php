<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Auth\AuthenticationException;
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

    /**
     * 列出這個產品的評價
     *
     * @unauthenticated
     */
    public function comments(Product $product)
    {
        $comments = $product->comments()->get();
        $comments->load('user');

        return CommentResource::collection($comments);
    }

    /**
     * 建立這個產品的評論
     */
    public function storeComment(Request $request, Product $product)
    {
        $input = $request->validate([
            /**
             * 評論內容
             */
            'content' => ['required', 'string', 'min:5', 'max:512'],
            /**
             * 評論星數
             */
            'rating' => ['required', 'numeric', 'min:1', 'max:5'],
        ]);

        if (! $request->user()) {
            throw new AuthenticationException('Unauthenticated.');
        }

        $comment = $product->comments()->create([
            ...$input,
            'user_id' => $request->user()->id,
        ]);

        return CommentResource::make($comment);
    }
}
