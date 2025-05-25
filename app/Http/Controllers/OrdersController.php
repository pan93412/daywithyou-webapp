<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class OrdersController extends Controller
{
    /**
     * Display the order confirmation page.
     *
     * @return \Inertia\Response
     */
    public function confirmation()
    {
        return Inertia::render('orders/confirmation');
    }
}
