@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-person-fill" aria-hidden="true"></i>
            </span>

            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Detail User</h1>
                <p class="text-muted mb-0">
                    Informasi lengkap data pengguna sistem.
                </p>
            </div>
        </div>

        <div class="heading-actions">
            <a class="btn btn-outline-secondary btn-sm"
               href="{{ route('users.index') }}">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Kembali
            </a>

            <a class="btn btn-primary btn-sm"
               href="{{ route('users.edit', $user->id) }}">
                <i class="bi bi-pencil" aria-hidden="true"></i>
                Edit User
            </a>
        </div>
    </div>

    <section class="row g-3">

        {{-- Profile User --}}
        <div class="col-12 col-xl-4">
            <div class="panel h-100 text-center profile-card">

                <div class="profile-cover">
                    <img src="{{ asset('img/damkar1.jpg') }}"
                         alt="User cover preview">
                </div>

                <div class="profile-hero">

                    <div class="avatar-img avatar-xl profile-photo bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                        <i class="bi bi-person-fill fs-1 text-primary"
                           aria-hidden="true"></i>
                    </div>

                    <h2 class="h5 mb-1">
                        {{ $user->name }}
                    </h2>

                    <p class="text-muted mb-3">
                        {{ $user->email }}
                    </p>

                    {{-- Role --}}
                    @if($user->role === 'admin')
                        <span class="badge text-bg-danger">
                            Admin
                        </span>
                    @elseif($user->role === 'petugas')
                        <span class="badge text-bg-primary">
                            Petugas
                        </span>
                    @elseif($user->role === 'pimpinan')
                        <span class="badge text-bg-warning">
                            Pimpinan
                        </span>
                    @else
                        <span class="badge text-bg-secondary">
                            {{ ucfirst($user->role) }}
                        </span>
                    @endif

                </div>

                <div class="info-list mt-4 text-start">

                    <div>
                        <span>Nama Lengkap</span>
                        <strong>{{ $user->name }}</strong>
                    </div>

                    <div>
                        <span>Email</span>
                        <strong>{{ $user->email }}</strong>
                    </div>

                    <div>
                        <span>Role</span>
                        <strong>{{ ucfirst($user->role) }}</strong>
                    </div>

                </div>

            </div>
        </div>

        {{-- Informasi User --}}
        <div class="col-12 col-xl-8">

            <div class="panel mb-3">

                <div class="panel-header">

                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-info-circle"
                               aria-hidden="true"></i>

                            <span>Informasi lengkap pengguna</span>
                        </h2>

                        <p class="text-muted mb-0">
                            Detail akun dan waktu pembuatan serta pembaruan data.
                        </p>
                    </div>

                    <div class="d-flex gap-2">

                        <a class="btn btn-outline-primary btn-sm"
                           href="{{ route('users.edit', $user->id) }}">
                            <i class="bi bi-pencil-square"
                               aria-hidden="true"></i>
                            Edit
                        </a>

                        @if(auth()->id() !== $user->id)
                            <form action="{{ route('users.destroy', $user->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash"
                                       aria-hidden="true"></i>
                                    Hapus
                                </button>

                            </form>
                        @endif

                    </div>

                </div>

                {{-- Activity --}}
                <div class="activity-list">

                    <div class="activity-item">

                        <span class="activity-dot bg-primary"></span>

                        <div>
                            <p class="mb-1 fw-semibold">
                                User Dibuat
                            </p>

                            <p class="text-muted small mb-0">
                                {{ $user->created_at->format('d M Y, H:i:s') }}
                                ({{ $user->created_at->diffForHumans() }})
                            </p>
                        </div>

                    </div>

                    <div class="activity-item">

                        <span class="activity-dot bg-success"></span>

                        <div>
                            <p class="mb-1 fw-semibold">
                                Terakhir Diperbarui
                            </p>

                            <p class="text-muted small mb-0">
                                {{ $user->updated_at->format('d M Y, H:i:s') }}
                                ({{ $user->updated_at->diffForHumans() }})
                            </p>
                        </div>

                    </div>

                    {{-- Status --}}
                    @if($user->created_at->eq($user->updated_at))

                        <div class="activity-item">

                            <span class="activity-dot bg-warning"></span>

                            <div>
                                <p class="mb-1 fw-semibold">
                                    Status
                                </p>

                                <p class="text-muted small mb-0">
                                    <span class="badge text-bg-warning">
                                        Belum pernah diperbarui
                                    </span>
                                </p>
                            </div>

                        </div>

                    @else

                        <div class="activity-item">

                            <span class="activity-dot bg-success"></span>

                            <div>
                                <p class="mb-1 fw-semibold">
                                    Status
                                </p>

                                <p class="text-muted small mb-0">
                                    <span class="badge text-bg-success">
                                        Data telah diperbarui
                                    </span>
                                </p>
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </section>
</div>

@endsection