<?php

// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Tampilkan daftar semua pengguna.
     * Display a listing of all users.
     */
    public function index(Request $request)
    {
        // Ambil semua pengguna
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Tampilkan form untuk mengedit pengguna.
     * Display the form to edit the specified user.
     */
    public function edit(string $id)
    {
        // Mencari user berdasarkan ID
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update data pengguna.
     * Update the specified user in the database.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input dari request
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $id],
        ]);

        // Mencari user berdasarkan ID
        $user = User::findOrFail($id);
        
        // Menyiapkan data untuk update
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Hanya update password jika ada input password
        if ($request->filled('password')) {
            // Validasi password
            $request->validate([
                'password' => ['string', 'min:8', 'confirmed'],
            ]);
            $userData['password'] = Hash::make($request->password);
        }

        // Melakukan update data user
        $user->update($userData);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Hapus pengguna.
     * Remove the specified user from storage.
     */
    public function destroy(string $id)
    {
        // Mencari user berdasarkan ID
        $user = User::findOrFail($id);
        
        // Cek apakah user memiliki akun seller yang terkait
        if ($user->seller) {
            return redirect()->route('admin.users.index')->with('error', 'Cannot delete user with an associated seller account.');
        }
        
        // Hapus user
        $user->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
