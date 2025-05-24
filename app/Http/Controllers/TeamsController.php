<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamsController extends Controller
{
    // Halaman untuk ADMIN
    public function index()
    {
        $teams = Team::all();
        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        return view('admin.teams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('team_images', 'public');
        }

        Team::create([
            'name' => $request->name,
            'role' => $request->role,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.teams.index')->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function edit(Team $team)
    {
        return view('admin.teams.edit', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $imagePath = $team->image;

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            // Simpan gambar baru
            $imagePath = $request->file('image')->store('team_images', 'public');
        }

        $team->update([
            'name' => $request->name,
            'role' => $request->role,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.teams.index')->with('success', 'Data anggota tim berhasil diperbarui.');
    }

    public function destroy(Team $team)
    {
        if ($team->image) {
            Storage::disk('public')->delete($team->image);
        }

        $team->delete();

        return redirect()->route('admin.teams.index')->with('success', 'Anggota tim berhasil dihapus.');
    }

    // Halaman untuk USER
    public function showUserTeams()
    {
        $teams = Team::select('id', 'name', 'role', 'image')->get();
        return view('main.teams', compact('teams'));
    }
}
