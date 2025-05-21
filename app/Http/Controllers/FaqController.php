<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Menampilkan halaman FAQ.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('main.faq'); // Pastikan view ini ada
    }
}
