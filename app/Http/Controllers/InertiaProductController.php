<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductResource;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InertiaProductController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if ($query) {
            $products = Product::where('name', 'like', "%$query%")->paginate(6);
        } else {
            $products = Product::paginate(6);
        }

        $data = ProductIndexResource::collection($products);

        return Inertia::render('products/index', [
            'reply' => $data,
            'query' => $query
        ]);
    }

    public function show(Product $product)
    {
        $productData = ProductResource::make($product);

        return Inertia::render('products/show', [
            'productData' => $productData,
            'commentsData' => Inertia::defer(fn () => $this->getCommentsData($product)),
        ]);
    }

    private function getCommentsData(Product $product)
    {
        return CommentResource::collection(
            $product->comments()->get()->load('user'),
        );
    }

    public function store(Request $request, Product $product)
    {
        $user = $request->user();
        $input = $request->validate([
            "content" => ["required", "string", "min:5", "max:512"],
            "star" => ["required", "numeric", "min:1", "max:5"]
        ]);

        Comment::create([
            "content" => $input['content'],
            "star" => (int) $input['star'],
            "product_id" => $product->id,
            "user_id" => $user->id
        ]);

        return to_route('products.show', ['product' => $product->id]);
    }
}
