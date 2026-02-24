<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            // Validasi email agar mengabaikan email user yang sedang diupdate
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:user,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // Update password hanya jika kolom password diisi
        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function delete(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.delete', compact('user'));
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        
        // Mencegah admin menghapus akunnya sendiri yang sedang dipakai login
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak dapat menghapus user yang sedang login!');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}