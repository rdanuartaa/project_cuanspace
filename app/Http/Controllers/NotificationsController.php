<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware(\App\Http\Middleware\AdminMiddleware::class);
    }

    public function index()
    {
        $notifications = Notification::with(['user', 'seller', 'admin'])->latest()->get();
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::all();
        $sellers = Seller::whereHas('user')->with('user')->get();
        return view('admin.notifications.create', compact('users', 'sellers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:100',
            'pesan' => 'required|string',
            'penerima' => 'required|in:semua,pengguna,seller,khusus',
            'user_id' => 'nullable|exists:users,id',
            'seller_id' => 'nullable|exists:users,id',
            'status' => 'required|in:terkirim',
        ]);

        if ($request->penerima === 'khusus') {
            if ($request->user_id && $request->seller_id) {
                return redirect()->back()->withErrors(['error' => 'Pilih hanya salah satu: user atau seller untuk notifikasi khusus.'])->withInput();
            }
            if (!$request->user_id && !$request->seller_id) {
                return redirect()->back()->withErrors(['error' => 'Pilih user atau seller untuk notifikasi khusus.'])->withInput();
            }
        } else {
            // Jika penerima bukan khusus, set user_id dan seller_id ke null
            $request->merge(['user_id' => null, 'seller_id' => null]);
        }

        $data = [
            'judul' => $request->judul,
            'pesan' => $request->pesan,
            'penerima' => $request->penerima,
            'user_id' => $request->user_id,
            'seller_id' => $request->seller_id,
            'status' => $request->status,
            'admin_id' => Auth::guard('admin')->id(),
        ];

        Notification::create($data);

        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi berhasil dibuat.');
    }

    public function edit(Notification $notification)
    {
        $users = User::all();
        $sellers = Seller::whereHas('user')->with('user')->get();
        return view('admin.notifications.edit', compact('notification', 'users', 'sellers'));
    }

    public function update(Request $request, Notification $notification)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:100',
            'pesan' => 'required|string',
            'penerima' => 'required|in:semua,pengguna,seller,khusus',
            'user_id' => 'nullable|exists:users,id',
            'seller_id' => 'nullable|exists:users,id',
            'status' => 'required|in:terkirim',
        ]);

        if ($request->penerima === 'khusus') {
            if ($request->user_id && $request->seller_id) {
                return redirect()->back()->withErrors(['error' => 'Pilih hanya salah satu: user atau seller untuk notifikasi khusus.'])->withInput();
            }
            if (!$request->user_id && !$request->seller_id) {
                return redirect()->back()->withErrors(['error' => 'Pilih user atau seller untuk notifikasi khusus.'])->withInput();
            }
        } else {
            $request->merge(['user_id' => null, 'seller_id' => null]);
        }

        $data = [
            'judul' => $validated['judul'],
            'pesan' => $validated['pesan'],
            'penerima' => $validated['penerima'],
            'user_id' => $validated['user_id'],
            'seller_id' => $validated['seller_id'],
            'status' => $validated['status'],
        ];

        $notification->update($data);

        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi berhasil diperbarui.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi berhasil dihapus.');
    }
}
