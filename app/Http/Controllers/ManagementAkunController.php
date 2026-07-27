<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ManagementAkunController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('role', 'like', "%$search%");
            });
        }

        // Gunakan pagination agar tampilan tidak berat
        $users = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('management-akun.index', compact('users'));
    }

    public function create()
    {
        return view('management-akun.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,karyawan',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'email.unique' => 'Email ini sudah terdaftar.'
        ]);

        DB::transaction(function () use ($request) {
            DB::table('authorized_emails')->insertOrIgnore(['email' => $request->email]);

            User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password),
            ]);
        });

        return redirect()->route('management-akun.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('management-akun.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,karyawan',
            'password' => 'nullable|min:6|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        // LOGIKA BARU: Mencegah sistem kehabisan Admin
        // Jika user yang diedit asalnya Admin, dan akan diubah menjadi Karyawan...
        if ($user->role === 'admin' && $request->role !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();

            // ...cek apakah dia adalah satu-satunya admin tersisa?
            if ($adminCount <= 1) {
                return back()->withErrors(['role' => 'Tindakan Ditolak: Harus ada minimal 1 Admin di dalam sistem. Anda tidak bisa mengubah role satu-satunya Admin.']);
            }
        }

        DB::transaction(function () use ($request, $user) {
            if ($request->email !== $user->email) {
                DB::table('authorized_emails')->insertOrIgnore(['email' => $request->email]);
            }

            $data = [
                'nama' => $request->nama,
                'email' => $request->email,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);
        });

        // Jika user mengubah role-nya sendiri menjadi karyawan, redirect ke beranda karena akses adminnya dicabut
        if ($user->id === Auth::id() && $user->role !== 'admin') {
            return redirect()->route('beranda')->with('success', 'Role Anda telah diubah menjadi Karyawan.');
        }

        return redirect()->route('management-akun.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // LOGIKA BARU: Mencegah penghapusan jika itu adalah admin terakhir di sistem
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->withErrors(['error' => 'Tindakan Ditolak: Harus ada minimal 1 Admin di dalam sistem. Anda tidak bisa menghapus satu-satunya Admin.']);
            }
        }

        $user->delete();

        // Jika admin menghapus akunnya sendiri, otomatis logout sistem agar tidak error
        if ($id == Auth::id()) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect()->route('login')->with('success', 'Akun Anda berhasil dihapus.');
        }

        return redirect()->route('management-akun.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
