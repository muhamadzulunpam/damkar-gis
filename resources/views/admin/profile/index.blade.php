@extends('layouts.admin')

@section('title', 'Profile & Activity')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                Profile & Activity
            </h4>

            <p class="text-muted mb-0">
                Kelola informasi akun dan lihat aktivitas Anda.
            </p>
        </div>
    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Validation Error --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-2">
                Terdapat kesalahan:
            </div>

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Tabs --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-bottom">

            <ul
                class="nav nav-tabs card-header-tabs"
                id="profileTabs"
                role="tablist"
            >

                {{-- Profile --}}
                <li class="nav-item" role="presentation">

                    <button
                        class="nav-link active"
                        id="profile-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#profile"
                        type="button"
                        role="tab"
                    >
                        <i class="bi bi-person me-2"></i>
                        Profile
                    </button>

                </li>


                {{-- Activity --}}
                <li class="nav-item" role="presentation">

                    <button
                        class="nav-link"
                        id="activity-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#activity"
                        type="button"
                        role="tab"
                    >
                        <i class="bi bi-clock-history me-2"></i>
                        Aktivitas Saya

                        <span class="badge bg-secondary ms-1">
                            {{ $activities->total() }}
                        </span>

                    </button>

                </li>

            </ul>

        </div>


        <div class="card-body">

            <div class="tab-content">


                {{-- ================================================= --}}
                {{-- PROFILE --}}
                {{-- ================================================= --}}

                <div
                    class="tab-pane fade show active"
                    id="profile"
                    role="tabpanel"
                >

                    <div class="row g-4">

                        {{-- Informasi Profile --}}
                        <div class="col-lg-7">

                            <div class="card border">

                                <div class="card-header bg-white">

                                    <h6 class="fw-bold mb-0">
                                        <i class="bi bi-person-circle me-2"></i>
                                        Informasi Profile
                                    </h6>

                                </div>


                                <div class="card-body">

                                    <form
                                        action="{{ route('profile.update') }}"
                                        method="POST"
                                    >

                                        @csrf
                                        @method('PUT')


                                        {{-- Nama --}}
                                        <div class="mb-3">

                                            <label class="form-label fw-semibold">
                                                Nama
                                            </label>

                                            <input
                                                type="text"
                                                name="name"
                                                class="form-control"
                                                value="{{ old('name', $user->name) }}"
                                                required
                                            >

                                        </div>


                                        {{-- Email --}}
                                        <div class="mb-3">

                                            <label class="form-label fw-semibold">
                                                Email
                                            </label>

                                            <input
                                                type="email"
                                                name="email"
                                                class="form-control"
                                                value="{{ old('email', $user->email) }}"
                                                required
                                            >

                                        </div>


                                        {{-- Role --}}
                                        <div class="mb-3">

                                            <label class="form-label fw-semibold">
                                                Role
                                            </label>

                                            <input
                                                type="text"
                                                class="form-control"
                                                value="{{ ucfirst($user->role) }}"
                                                disabled
                                            >

                                            <div class="form-text">
                                                Role hanya dapat diubah melalui
                                                Manajemen User.
                                            </div>

                                        </div>


                                        {{-- Bergabung --}}
                                        <div class="mb-4">

                                            <label class="form-label fw-semibold">
                                                Bergabung Sejak
                                            </label>

                                            <input
                                                type="text"
                                                class="form-control"
                                                value="{{ $user->created_at->format('d F Y') }}"
                                                disabled
                                            >

                                        </div>


                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                        >
                                            <i class="bi bi-save me-1"></i>
                                            Simpan Perubahan
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>


                        {{-- Ubah Password --}}
                        <div class="col-lg-5">

                            <div class="card border">

                                <div class="card-header bg-white">

                                    <h6 class="fw-bold mb-0">
                                        <i class="bi bi-shield-lock me-2"></i>
                                        Keamanan Akun
                                    </h6>

                                </div>


                                <div class="card-body">

                                    <form
                                        action="{{ route('profile.password') }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('PUT')

                                        {{-- Password Lama --}}
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                Password Saat Ini
                                            </label>

                                            <div class="input-group">
                                                <input
                                                    type="password"
                                                    name="current_password"
                                                    id="current_password"
                                                    class="form-control"
                                                    autocomplete="current-password"
                                                    required
                                                >
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-secondary toggle-password"
                                                    data-target="current_password"
                                                    aria-label="Tampilkan password"
                                                >
                                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Password Baru --}}
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                Password Baru
                                            </label>

                                            <div class="input-group">
                                                <input
                                                    type="password"
                                                    name="password"
                                                    id="new_password"
                                                    class="form-control"
                                                    autocomplete="new-password"
                                                    required
                                                >
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-secondary toggle-password"
                                                    data-target="new_password"
                                                    aria-label="Tampilkan password"
                                                >
                                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                                </button>
                                            </div>

                                            <div class="form-text">
                                                Minimal 8 karakter, kombinasi huruf besar, huruf kecil, angka, dan simbol (contoh: ~ * ! @ #).
                                            </div>
                                        </div>

                                        {{-- Konfirmasi --}}
                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">
                                                Konfirmasi Password
                                            </label>

                                            <div class="input-group">
                                                <input
                                                    type="password"
                                                    name="password_confirmation"
                                                    id="confirm_password"
                                                    class="form-control"
                                                    autocomplete="new-password"
                                                    required
                                                >
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-secondary toggle-password"
                                                    data-target="confirm_password"
                                                    aria-label="Tampilkan password"
                                                >
                                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <button
                                            type="submit"
                                            class="btn btn-warning"
                                        >
                                            <i class="bi bi-key me-1"></i>
                                            Ubah Password
                                        </button>
                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- ACTIVITY --}}
                {{-- ================================================= --}}

                <div
                    class="tab-pane fade"
                    id="activity"
                    role="tabpanel"
                >

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <h6 class="fw-bold mb-1">
                                Aktivitas Saya
                            </h6>

                            <small class="text-muted">
                                Riwayat aktivitas yang Anda lakukan di dalam sistem.
                            </small>

                        </div>

                    </div>


                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th width="60">
                                        #
                                    </th>

                                    <th>
                                        Aktivitas
                                    </th>

                                    <th>
                                        Deskripsi
                                    </th>

                                    <th>
                                        Modul
                                    </th>

                                    <th>
                                        IP Address
                                    </th>

                                    <th>
                                        Waktu
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($activities as $index => $activity)

                                    <tr>

                                        <td>
                                            {{ $activities->firstItem() + $index }}
                                        </td>


                                        <td>

                                            @php

                                                $badgeClass = match($activity->action) {

                                                    'create' => 'bg-success',

                                                    'update' => 'bg-warning text-dark',

                                                    'delete' => 'bg-danger',

                                                    'login' => 'bg-primary',

                                                    'logout' => 'bg-secondary',

                                                    'process' => 'bg-info text-dark',

                                                    default => 'bg-dark',

                                                };

                                            @endphp


                                            <span class="badge {{ $badgeClass }}">
                                                {{ $activity->activity }}
                                            </span>

                                        </td>


                                        <td>
                                            {{ $activity->description }}
                                        </td>


                                        <td>
                                            {{ $activity->module ?? '-' }}
                                        </td>


                                        <td>
                                            <code>
                                                {{ $activity->ip_address ?? '-' }}
                                            </code>
                                        </td>


                                        <td>

                                            <div>
                                                {{ $activity->created_at->format('d M Y') }}
                                            </div>

                                            <small class="text-muted">
                                                {{ $activity->created_at->format('H:i:s') }}
                                            </small>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="6"
                                            class="text-center py-5"
                                        >

                                            <i
                                                class="bi bi-clock-history fs-1 text-muted"
                                            ></i>

                                            <div class="mt-2 text-muted">
                                                Belum ada aktivitas.
                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- Pagination --}}
                    @if($activities->hasPages())

                        <div class="mt-3">

                            {{ $activities->links() }}

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

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