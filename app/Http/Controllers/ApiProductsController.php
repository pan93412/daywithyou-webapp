<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ApiProductsController extends Controller
{
    public function index(Request $request)
    {
        /**
         * the number of items per page
         */
        $perPage = $request->integer('per_page', 10);
        /**
         * the current page
         */
        $page = $request->integer('page', 1);

        $products = Product::paginate($perPage, page: $page);

        return ProductIndexResource::collection($products);
    }

    public function show(Product $product)
    {
        return ProductResource::make($product);
    }
}
