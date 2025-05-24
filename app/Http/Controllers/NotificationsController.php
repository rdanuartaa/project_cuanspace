<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use App\Models\Seller;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications = Notification::all();
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::all();
        $sellers = Seller::all();
        return view('admin.notifications.create', compact('users', 'sellers'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'pesan' => 'required|string',
            'penerima' => 'required|in:semua,user,seller,khusus',
            'khusus_type' => 'required_if:penerima,khusus|in:user,seller',
            'user_id' => 'nullable|exists:users,id',
            'seller_brand_name' => 'nullable|exists:sellers,brand_name',
        ]);

        $validator->sometimes('user_id', 'required', function ($input) {
            return $input->penerima === 'khusus' && $input->khusus_type === 'user';
        });

        $validator->sometimes('seller_brand_name', 'required', function ($input) {
            return $input->penerima === 'khusus' && $input->khusus_type === 'seller';
        });

        $validator->validate();

        $data = [
            'judul' => $request->judul,
            'pesan' => $request->pesan,
            'penerima' => $request->penerima,
            'khusus_type' => null,
            'user_id' => null,
            'seller_brand_name' => null,
            'status' => 'terkirim',
            'admin_id' => auth()->id(),
        ];

        if ($request->penerima == 'khusus') {
            $data['khusus_type'] = $request->khusus_type;
            if ($request->khusus_type == 'user') {
                $data['user_id'] = $request->user_id;
            } elseif ($request->khusus_type == 'seller') {
                $data['seller_brand_name'] = $request->seller_brand_name;
            }
        }

        Notification::create($data);

        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi berhasil dibuat.');
    }

    public function edit(Notification $notification)
{
    $users = User::all();
    $sellers = Seller::all();
    return view('admin.notifications.edit', compact('notification', 'users', 'sellers'));
}

public function update(Request $request, Notification $notification)
{
    $validator = \Validator::make($request->all(), [
        'judul' => 'required|string|max:255',
        'pesan' => 'required|string',
        'penerima' => 'required|in:semua,user,seller,khusus',
        'khusus_type' => 'nullable|in:user,seller|required_if:penerima,khusus',
        'user_id' => 'nullable|exists:users,id',
        'seller_brand_name' => 'nullable|exists:sellers,brand_name',
    ]);

    $validator->sometimes('user_id', 'required', function ($input) {
        return $input->penerima === 'khusus' && $input->khusus_type === 'user';
    });

    $validator->sometimes('seller_brand_name', 'required', function ($input) {
        return $input->penerima === 'khusus' && $input->khusus_type === 'seller';
    });

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $validated = $validator->validated();

    $data = [
        'judul' => $validated['judul'],
        'pesan' => $validated['pesan'],
        'penerima' => $validated['penerima'],
        'khusus_type' => null,
        'user_id' => null,
        'seller_brand_name' => null,
    ];

    if ($validated['penerima'] === 'khusus') {
        $data['khusus_type'] = $validated['khusus_type'];

        if ($validated['khusus_type'] === 'user') {
            $data['user_id'] = $validated['user_id'];
        } elseif ($validated['khusus_type'] === 'seller') {
            $data['seller_brand_name'] = $validated['seller_brand_name'];
        }
    }

    $notification->update($data);

    return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi berhasil diperbarui.');
}

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi berhasil dihapus.');
    }
}
