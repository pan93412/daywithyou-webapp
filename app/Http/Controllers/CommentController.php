<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Product $product)
    {
        $comments = Comment::where("product_id", $product->id)
            ->get()
            ->load("user");

        return CommentResource::collection($comments);
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

        return response()->json(
            $comment->toResource(),
            201
        );
    }
}
