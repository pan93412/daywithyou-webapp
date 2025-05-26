<?php

namespace App\Http\Controllers;

use App\Models\Comment;

class DashboardController extends Controller
{
    public function index()
    {
        $commentCount = Comment::where('user_id', auth()->user()->id)->count();

        return inertia('dashboard', [
            'commentCount' => $commentCount,
        ]);
    }
}
