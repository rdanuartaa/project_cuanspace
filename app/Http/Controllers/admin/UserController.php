<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Tampilkan daftar semua pengguna.
     */
    public function index(Request $request)
    {
        // Ambil semua pengguna
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Tampilkan form untuk mengedit pengguna.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update data pengguna.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            // Update password hanya jika diisi
            'password' => $request->filled('password') ? bcrypt($request->input('password')) : $user->password,
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User updated successfully!');
    }

    /**
     * Hapus pengguna.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'User deleted successfully!');
    }
}
