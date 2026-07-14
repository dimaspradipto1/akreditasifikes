<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('pages.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validRoles = implode(',', array_keys(\App\Models\User::availableRoles()));

        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:6', 'confirmed'],
            'role'      => ['required', 'string', 'in:' . $validRoles],
            'is_active' => ['nullable'],
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'role.required'     => 'Hak akses wajib dipilih.',
            'role.in'           => 'Hak akses tidak valid.',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'is_active' => $request->boolean('is_active', true),
        ]);

        Alert::success('Berhasil!', 'Pengguna berhasil ditambahkan.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return redirect()->route('user.edit', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('pages.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validRoles = implode(',', array_keys(\App\Models\User::availableRoles()));

        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'unique:users,email,' . $user->id],
            'role'      => ['required', 'string', 'in:' . $validRoles],
            'is_active' => ['nullable'],
        ], [
            'name.required'  => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan pengguna lain.',
            'role.required'  => 'Hak akses wajib dipilih.',
            'role.in'        => 'Hak akses tidak valid.',
        ]);

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'is_active' => $request->boolean('is_active'),
        ]);

        Alert::success('Berhasil!', 'Data pengguna berhasil diperbarui.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            Alert::error('Gagal!', 'Anda tidak dapat menghapus akun sendiri.')
                ->toToast()->autoclose(3000)->timerProgressBar();
            return redirect()->route('user.index');
        }

        $user->delete();

        Alert::success('Berhasil!', 'Pengguna berhasil dihapus.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->route('user.index');
    }

    /**
     * Tampilkan form update password pengguna
     */
    public function updatePasswordForm(User $user)
    {
        return view('pages.users.update-password', compact('user'));
    }

    /**
     * Proses update password pengguna
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password'              => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'password.required'              => 'Password baru wajib diisi.',
            'password.min'                   => 'Password minimal 6 karakter.',
            'password.confirmed'             => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        Alert::success('Berhasil!', 'Password pengguna "' . $user->name . '" berhasil diperbarui.')
            ->toToast()->autoclose(4000)->timerProgressBar();

        return redirect()->route('user.index');
    }
}
