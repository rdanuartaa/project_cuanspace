<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::select('id', 'nama_kategori', 'slug')->get();
        return response()->json([
            'success' => true,
            'data' => $kategoris,
        ]);
    }
}
