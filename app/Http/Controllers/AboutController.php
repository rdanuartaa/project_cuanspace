<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Menampilkan halaman about.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('main.about'); // Pastikan view ini ada
    }
}
