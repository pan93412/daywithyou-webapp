<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductResource;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InertiaProductController extends Controller
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

    public function store(Request $request, Product $product)
    {
        $user = $request->user();
        $input = $request->validate([
            "content" => ["required", "string", "min:5", "max:512"],
            "star" => ["required", "numeric", "min:1", "max:5"]
        ]);

        $comment = Comment::create([
            "content" => $input['content'],
            "star" => (int) $input['star'],
            "product_id" => $product->id,
            "user_id" => $user->id
        ]);
    }
}
