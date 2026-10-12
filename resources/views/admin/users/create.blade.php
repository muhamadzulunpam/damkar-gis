@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-plus-circle" aria-hidden="true"></i>
            </span>

            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Tambah User</h1>
                <p class="text-muted mb-0">
                    Tambahkan akun pengguna baru ke dalam sistem.
                </p>
            </div>
        </div>

        <div class="heading-actions">
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Kembali
            </a>
        </div>
    </div>

    <section class="row g-3">
        <div class="col-12">
            <form
                action="{{ route('users.store') }}"
                method="POST"
                class="panel"
            >
                @csrf

                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-person-plus" aria-hidden="true"></i>
                            <span>Informasi User</span>
                        </h2>

                        <p class="text-muted mb-0">
                            Lengkapi informasi akun pengguna.
                        </p>
                    </div>
                </div>

                <div class="row g-3">

                    {{-- Nama --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap"
                            required
                        >

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="contoh@email.com"
                            required
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="col-md-6">
                        <label for="password" class="form-label">
                            Password
                        </label>

                        <div class="input-group has-validation">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Masukkan password"
                                required
                            >

                            <button
                                type="button"
                                class="btn btn-outline-secondary toggle-password"
                                data-target="password"
                                aria-label="Tampilkan password"
                            >
                                <i class="bi bi-eye" aria-hidden="true"></i>
                            </button>

                            @error('password')
                                <div class="invalid-feedback d-block">
                                    @foreach ($errors->get('password') as $message)
                                        <div>{{ $message }}</div>
                                    @endforeach
                                </div>
                            @enderror
                        </div>

                        <div class="form-text">
                            Minimal 8 karakter, kombinasi huruf besar, huruf kecil, angka, dan simbol (contoh: ~ * ! @ #).
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">
                            Konfirmasi Password
                        </label>

                        <div class="input-group">
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                placeholder="Ulangi password"
                                required
                            >

                            <button
                                type="button"
                                class="btn btn-outline-secondary toggle-password"
                                data-target="password_confirmation"
                                aria-label="Tampilkan password"
                            >
                                <i class="bi bi-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Role --}}
                    <div class="col-md-6">
                        <label for="role" class="form-label">
                            Role
                        </label>

                        <select
                            name="role"
                            id="role"
                            class="form-select @error('role') is-invalid @enderror"
                            required
                        >
                            <option value="">Pilih Role</option>

                            <option
                                value="admin"
                                {{ old('role') == 'admin' ? 'selected' : '' }}
                            >
                                Admin
                            </option>

                            <option
                                value="petugas"
                                {{ old('role') == 'petugas' ? 'selected' : '' }}
                            >
                                Petugas
                            </option>

                            <option
                                value="pimpinan"
                                {{ old('role') == 'pimpinan' ? 'selected' : '' }}
                            >
                                Pimpinan
                            </option>
                        </select>

                        @error('role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                {{-- Actions --}}
                <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">

                    <a
                        href="{{ route('users.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-person-check" aria-hidden="true"></i>
                        Tambah User
                    </button>

                </div>
            </form>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.toggle-password').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.target);
            const icon  = this.querySelector('i');
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';

            icon.classList.toggle('bi-eye', !isHidden);
            icon.classList.toggle('bi-eye-slash', isHidden);

            this.setAttribute(
                'aria-label',
                isHidden ? 'Sembunyikan password' : 'Tampilkan password'
            );
        });
    });
</script>
@endpush

@endsection      