<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ApiProductsController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return ProductIndexResource::collection($products);
    }

    public function show(Product $product)
    {
        return ProductResource::make($product);
    }
}
