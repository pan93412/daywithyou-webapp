<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductIndexResource;
use App\Models\Product;
use Inertia\Inertia;

class InertiaHomeController extends Controller
{
    public function index()
    {
        $hotProductsData = ProductIndexResource::collection(
            Product::limit(3)->get()
        );

        // Sample data for the new components
        $newsItems = [
            [
                'id' => 1,
                'title' => '新品上市：限量版手工藝品現已推出',
                'url' => '/news/1',
            ],
            [
                'id' => 2,
                'title' => '夏季特惠：全場商品8折起',
                'url' => '/news/2',
            ],
            [
                'id' => 3,
                'title' => '會員專屬活動：購物滿NT$2000送精美禮品',
                'url' => '/news/3',
            ],
        ];

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
            'newsItems' => $newsItems,
            'testimonials' => $testimonials,
        ]);
    }
}
