<?php

namespace App\Http\Controllers;

use App\DataTables\AuthDataTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('layouts.auth.login');
    }

    /**
     * Proses login
     */
    public function loginProses(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        // Cek user & status aktif
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.'])->withInput();
        }

        if (!$user->is_active) {
            return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.'])->withInput();
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        return back()->withErrors(['password' => 'Password yang Anda masukkan salah.'])->withInput();
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Alert::success('Anda telah berhasil keluar.')
            ->toToast()
            ->autoclose(3000)
            ->timerProgressBar();
        return redirect()->route('login');
    }


    /**
     * Proses registrasi / tambah pengguna baru (oleh admin)
     */
    public function registerProses(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::min(8)->mixedCase()->numbers(), 'confirmed'],
            'role'     => ['required', 'in:admin,operator,asesor,prodi'],
            'is_active'=> ['boolean'],
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required'      => 'Role wajib dipilih.',
            'role.in'            => 'Role tidak valid.',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Switch role (khusus untuk admin)
     */
    public function switchRole($role)
    {
        // Hanya admin yang bisa initiate switch, ATAU orang yang sedang menggunakan fitur impersonate (jika mau kembali ke admin, opsional)
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Akses ditolak. Anda bukan admin.');
        }

        // Cari user pertama yang memiliki role tersebut
        $targetUser = User::where('role', $role)->first();

        if (!$targetUser) {
            return back()->with('error', 'Tidak ada akun dengan role ' . $role . ' di dalam database.');
        }

        // Simpan sesi bahwa ini adalah admin yang sedang menyamar
        session()->put('impersonated_by', Auth::id());

        Auth::login($targetUser);

        return redirect()->route('dashboard')->with('success', 'Berhasil beralih peran (Login As) menjadi ' . $targetUser->name);
    }

    /**
     * Kembali ke akun admin
     */
    public function switchBack()
    {
        if (session()->has('impersonated_by')) {
            $adminId = session()->pull('impersonated_by');
            $adminUser = User::find($adminId);
            
            if ($adminUser) {
                Auth::login($adminUser);
                return redirect()->route('dashboard')->with('success', 'Berhasil kembali ke akun Admin.');
            }
        }
        
        return back();
    }
}
