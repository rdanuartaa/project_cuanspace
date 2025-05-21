<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamsController extends Controller
{
    /**
     * Menampilkan halaman FAQ.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('main.teams'); // Pastikan view ini ada
    }
}
