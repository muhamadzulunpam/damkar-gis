<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Helpers\ActivityLogger; 
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Query user
        $query = User::query();

        // Search nama atau email
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Ambil data user setelah filtering
        $users = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Statistik user
        $totalAdmin = User::where('role', 'admin')->count();
        $totalPetugas = User::where('role', 'petugas')->count();
        $totalPimpinan = User::where('role', 'pimpinan')->count();

        return view('admin.users.index', [
            'title' => 'Users',
            'users' => $users,
            'totalAdmin' => $totalAdmin,
            'totalPetugas' => $totalPetugas,
            'totalPimpinan' => $totalPimpinan,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create', [
            'title' => 'Create User',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'petugas', 'pimpinan'])],
        ], [
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.mixed'     => 'Password harus mengandung huruf besar dan huruf kecil.',
            'password.numbers'   => 'Password harus mengandung minimal 1 angka.',
            'password.symbols'   => 'Password harus mengandung minimal 1 simbol (contoh: ~ * ! @ #).',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        ActivityLogger::log(
            'Create',
            'Menambahkan User "' . $user->name . '".',
            'User',
            'create'
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'), [
            'title' => 'User Details',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'), [
            'title' => 'Edit User',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'role' => ['required', Rule::in([
                'admin',
                'petugas',
                'pimpinan',
            ])],

            'password' => ['nullable', 'confirmed', Password::defaults()],
        ], [
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.mixed'     => 'Password harus mengandung huruf besar dan huruf kecil.',
            'password.numbers'   => 'Password harus mengandung minimal 1 angka.',
            'password.symbols'   => 'Password harus mengandung minimal 1 simbol (contoh: ~ * ! @ #).',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        // Password hanya diubah jika diisi
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        ActivityLogger::log(
            'Update',
            'Memperbarui User "' . $user->name . '".',
            'User',
            'update'
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
         if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        ActivityLogger::log(
            'Delete',
            'Menghapus User "' . $user->name . '".',
            'User',
            'delete'
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
