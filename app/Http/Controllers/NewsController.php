<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsIndexResource;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index()
    {
        $newsReply = NewsIndexResource::collection(
            News::paginate(6)
        );

        return Inertia::render('news/index', [
            'newsReply' => $newsReply,
        ]);
    }

    public function show(News $news)
    {
        return Inertia::render('news/show', [
            'newsReply' => NewsResource::make($news),
        ]);
    }
}
