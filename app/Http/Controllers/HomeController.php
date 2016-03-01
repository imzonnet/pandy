<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Dashboard';
        return view('home', compact('title'));
    }

    /**
     * Show the payment calculator
     */
    public function calculator()
    {
        return view('calculator');
    }
}
