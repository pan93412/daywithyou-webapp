<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsIndexResource;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Inertia\Inertia;

class InertiaNewsController extends Controller
{
    public function index()
    {
        $paginatedNewsData = NewsIndexResource::collection(
            News::paginate(6)
        );

        return Inertia::render('news/index', [
            'paginatedNewsData' => $paginatedNewsData,
        ]);
    }

    public function show(string $slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();

        return Inertia::render('news/show', [
            'newsData' => NewsResource::make($news),
        ]);
    }
}
