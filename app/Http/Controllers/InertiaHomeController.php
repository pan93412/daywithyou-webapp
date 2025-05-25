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
            Product::all()
        );

        return Inertia::render('home', [
            'hotProductsData' => $hotProductsData
        ]);
    }
}
