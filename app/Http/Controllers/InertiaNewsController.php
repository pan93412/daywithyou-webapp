<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductIndexResource;
use App\Models\Product;
use Inertia\Inertia;

class InertiaNewsController extends Controller
{
    public function index()
    {
        $paginatedProductsData = ProductIndexResource::collection(
            Product::paginate(6)
        );

        return Inertia::render('products/index', [
            'paginatedProductsData' => $paginatedProductsData
        ]);
    }
}
