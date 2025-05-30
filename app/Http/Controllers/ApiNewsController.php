<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsIndexResource;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;

#[Group('最新消息')]
class ApiNewsController extends Controller
{
    /**
     * 取得最新消息列表
     *
     * 回傳分頁的最新消息列表。
     *
     * @unauthenticated
     */
    public function index(Request $request)
    {
        /**
         * 每頁顯示的項目數量
         */
        $perPage = $request->integer('per_page', 10);
        /**
         * 目前頁數
         */
        $page = $request->integer('page', 1);

        $news = News::paginate($perPage, page: $page);

        return NewsIndexResource::collection($news);
    }

    /**
     * 取得特定最新消息
     *
     * 根據 `slug` 取得特定最新消息的詳細資訊。
     *
     * @unauthenticated
     */
    public function show(News $news)
    {
        return NewsResource::make($news);
    }
}
