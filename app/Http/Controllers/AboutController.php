<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    // Halaman About untuk ADMIN
    public function index()
    {
        $abouts = About::all();
        return view('admin.about.index', compact('abouts'));
    }

    public function create()
    {
        return view('admin.about.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'visi' => 'required',
            'misi' => 'required',
            'status' => 'required|in:Published,Draft',
        ]);

        // Upload file
        $thumbnailPath = $request->file('thumbnail')->store('about_thumbnails', 'public');

        About::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'thumbnail' => $thumbnailPath,
            'visi' => $request->visi,
            'misi' => $request->misi,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.about.index')->with('success', 'About berhasil ditambahkan');
    }

    public function edit(About $about)
    {
        return view('admin.about.edit', compact('about'));
    }

    public function update(Request $request, About $about)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'visi' => 'required',
            'misi' => 'required',
            'status' => 'required|in:Published,Draft',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Hapus file lama jika ada
            if ($about->thumbnail) {
                Storage::disk('public')->delete($about->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('about_thumbnails', 'public');
        } else {
            $thumbnailPath = $about->thumbnail;
        }

        $about->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'thumbnail' => $thumbnailPath,
            'visi' => $request->visi,
            'misi' => $request->misi,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.about.index')->with('success', 'About berhasil diupdate');
    }

    public function destroy(About $about)
    {
        if ($about->thumbnail) {
            Storage::disk('public')->delete($about->thumbnail);
        }

        $about->delete();

        return redirect()->route('admin.about.index')->with('success', 'About berhasil dihapus');
    }

    // Halaman About untuk USER
    public function showUserAbout()
    {
        $about = About::where('status', 'Published')->first(); // hanya tampilkan yang published
        return view('main.about', compact('about'));
    }
}
