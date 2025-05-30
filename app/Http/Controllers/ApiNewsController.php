<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsIndexResource;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;

class ApiNewsController extends Controller
{
    public function index(Request $request)
    {
        /**
         * the number of items per page
         */
        $perPage = $request->integer('per_page', 10);
        /**
         * the current page
         */
        $page = $request->integer('page', 1);

        $news = News::paginate($perPage, page: $page);

        return NewsIndexResource::collection($news);
    }

    public function show(News $news)
    {
        return NewsResource::make($news);
    }
}
