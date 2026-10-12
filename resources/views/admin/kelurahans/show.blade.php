@extends('layouts.admin')

@section('title', 'Detail Kelurahan')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
            </span>
            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Detail Kelurahan</h1>
                <p class="text-muted mb-0">
                    Informasi lengkap data kelurahan.
                </p>
            </div>
        </div>
        <div class="heading-actions">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('kelurahans.index') }}">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Kembali
            </a>
            <a class="btn btn-primary btn-sm" href="{{ route('kelurahans.edit', $kelurahan->id) }}">
                <i class="bi bi-pencil" aria-hidden="true"></i>
                Edit Kelurahan
            </a>
        </div>
    </div>

    <section class="row g-3">
        <div class="col-12 col-xl-4">
            <div class="panel h-100 text-center profile-card">
                <div class="profile-cover">
                    <img src="{{ asset('img/damkar1.jpg') }}" alt="Kelurahan cover preview">
                </div>
                <div class="profile-hero">
                    <div class="avatar-img avatar-xl profile-photo bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                        <i class="bi bi-building fs-1 text-primary" aria-hidden="true"></i>
                    </div>
                    <h2 class="h5 mb-1">{{ $kelurahan->nama }}</h2>
                    <p class="text-muted mb-3">
                        <span class="badge text-bg-info">{{ $kelurahan->kecamatan->nama ?? '-' }}</span>
                    </p>
                    <span class="badge text-bg-success">Active</span>
                </div>
                <div class="info-list mt-4 text-start">
                    <div>
                        <span>Nama Kelurahan</span>
                        <strong>{{ $kelurahan->nama }}</strong>
                    </div>
                    <div>
                        <span>Kecamatan</span>
                        <strong>{{ $kelurahan->kecamatan->nama ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>Keterangan</span>
                        <strong>{{ $kelurahan->keterangan ?? '-' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="panel mb-3">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-info-circle" aria-hidden="true"></i>
                            <span>Informasi lengkap data kelurahan.</span>
                        </h2>
                        <p class="text-muted mb-0">
                            Detail waktu pembuatan dan pembaruan data.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('kelurahans.edit', $kelurahan->id) }}">
                            <i class="bi bi-pencil-square" aria-hidden="true"></i>
                            Edit
                        </a>
                        <form action="{{ route('kelurahans.destroy', $kelurahan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelurahan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash" aria-hidden="true"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <span class="activity-dot bg-primary"></span>
                        <div>
                            <p class="mb-1 fw-semibold">Data Kelurahan Dibuat</p>
                            <p class="text-muted small mb-0">
                                {{ $kelurahan->created_at->format('d M Y, H:i:s') }}
                                ({{ $kelurahan->created_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>
                    <div class="activity-item">
                        <span class="activity-dot bg-success"></span>
                        <div>
                            <p class="mb-1 fw-semibold">Terakhir Diperbarui</p>
                            <p class="text-muted small mb-0">
                                {{ $kelurahan->updated_at->format('d M Y, H:i:s') }}
                                ({{ $kelurahan->updated_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>
                    @if($kelurahan->created_at->eq($kelurahan->updated_at))
                    <div class="activity-item">
                        <span class="activity-dot bg-warning"></span>
                        <div>
                            <p class="mb-1 fw-semibold">Status</p>
                            <p class="text-muted small mb-0">
                                <span class="badge text-bg-warning">Belum pernah diperbarui</span>
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