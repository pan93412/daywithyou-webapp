<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductIndexResource;
use App\Models\News;
use App\Models\Product;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InertiaHomeController extends Controller
{
    public function index()
    {
        $hotProductsData = ProductIndexResource::collection(
            Product::limit(3)->get()
        );

        $testimonials = [
            [
                'id' => 1,
                'name' => '陳小明',
                'avatar' => '/images/avatars/user1.jpg',
                'text' => '商品質量非常好，包裝精美，很適合送禮！',
                'rating' => 5,
            ],
            [
                'id' => 2,
                'name' => '林美玲',
                'avatar' => '/images/avatars/user2.jpg',
                'text' => '收到的商品比照片上看起來還要精緻，非常滿意。',
                'rating' => 4,
            ],
            [
                'id' => 3,
                'name' => '王大華',
                'avatar' => '/images/avatars/user3.jpg',
                'text' => '送貨速度快，客服態度很好，會再來購買。',
                'rating' => 5,
            ],
        ];

        return Inertia::render('home', [
            'hotProductsData' => $hotProductsData,
            'newsItems' => News::limit(5)
                ->orderBy('id', 'desc')
                ->select(['slug', 'title'])
                ->get(),
            'testimonials' => $testimonials,
        ]);
    }

    public function subscribe(Request $request)
    {
        $input = $request->validate([
            'email' => ['required', 'email'],
        ]);

        Subscriber::firstOrCreate($input);
    }
}
