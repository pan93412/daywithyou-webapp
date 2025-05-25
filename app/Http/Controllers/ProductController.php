<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $hotProductsData = ProductIndexResource::collection(
            Product::all()
        );

        return Inertia::render('home', [
            'hotProductsData' => $hotProductsData
        ]);
    }

    public function show(Product $product)
    {
        $productData = ProductResource::make($product);

        return Inertia::render('products/show', [
            'productData' => $productData,
        ]);
    }
}
