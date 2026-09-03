<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Notifications\NewUserAccountNotification;
use App\Notifications\EmailUpdatedNotification;

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
            'nama' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:users,email',
            'role' => 'required|in:admin,karyawan',
        ], [
            'nama.required' => 'Nama pengguna wajib diisi.',
            'nama.max' => 'Nama maksimal berisi 50 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal berisi 50 karakter.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Pilihan role tidak valid.'
        ]);

        // Generate password acak yang kuat (mengandung huruf besar, kecil, angka, dan simbol)
        $rawPassword = Str::random(10) . 'Aa1@';

        DB::transaction(function () use ($request, $rawPassword) {
            DB::table('authorized_emails')->insertOrIgnore(['email' => $request->email]);

            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($rawPassword),
            ]);

            // Kirim email berisi password ke user baru
            $user->notify(new NewUserAccountNotification($rawPassword));
        });

        return redirect()->route('management-akun.index')->with('success', 'Pengguna berhasil ditambahkan dan password telah dikirim ke email!');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('management-akun.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $oldEmail = $user->email; // Simpan email lama untuk pengecekan

        $request->validate([
            'nama' => 'required|string|max:50',
            'email' => ['required', 'email', 'max:50', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,karyawan',
        ], [
            'nama.required' => 'Nama pengguna wajib diisi.',
            'nama.max' => 'Nama maksimal berisi 50 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal berisi 50 karakter.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Pilihan role tidak valid.'
        ]);

        if ($user->role === 'admin' && $request->role !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->withErrors(['role' => 'Tindakan Ditolak: Harus ada minimal 1 Admin di dalam sistem.']);
            }
        }

        DB::transaction(function () use ($request, $user, $oldEmail) {
            if ($request->email !== $user->email) {
                DB::table('authorized_emails')->insertOrIgnore(['email' => $request->email]);
            }

            $user->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'role' => $request->role,
            ]);

            // Kirim notifikasi JIKA email berubah
            if ($oldEmail !== $request->email) {
                $user->notify(new EmailUpdatedNotification());
            }
        });

        if ($user->id === Auth::id() && $user->role !== 'admin') {
            return redirect()->route('beranda')->with('success', 'Role Anda telah diubah menjadi Karyawan.');
        }

        return redirect()->route('management-akun.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Mencegah penghapusan jika itu adalah admin terakhir di sistem
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
