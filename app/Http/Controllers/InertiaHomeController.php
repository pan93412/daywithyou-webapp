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

        return Inertia::render('home', [
            'hotProductsData' => $hotProductsData
        ]);
    }
}
