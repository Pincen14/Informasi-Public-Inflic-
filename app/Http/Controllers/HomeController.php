<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        // data dummy dulu (biar blade kamu jalan)
        $products = [
            ['name' => 'Headphone', 'price' => '$29.90'],
            ['name' => 'CCTV', 'price' => '$50.00'],
            ['name' => 'Speaker', 'price' => '$99.00'],
        ];

        return view('homepage', compact('products'));
    }
}
