<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsIndexResource;
use App\Http\Resources\NewsResource;
use App\Models\News;

class ApiNewsController extends Controller
{
    public function index()
    {
        $news = News::all();
        return NewsIndexResource::collection($news);
    }

    public function show(News $news)
    {
        return NewsResource::make($news);
    }
}
