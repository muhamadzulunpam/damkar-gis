@extends('layouts.admin')

@section('title', 'Detail Kecamatan')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
            </span>
            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Detail Kecamatan</h1>
                <p class="text-muted mb-0">
                    Informasi lengkap data kecamatan dan daftar kelurahan.
                </p>
            </div>
        </div>
        <div class="heading-actions">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('kecamatans.index') }}">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Kembali
            </a>
            <a class="btn btn-primary btn-sm" href="{{ route('kecamatans.edit', $kecamatan->id) }}">
                <i class="bi bi-pencil" aria-hidden="true"></i>
                Edit Kecamatan
            </a>
        </div>
    </div>

    <section class="row g-3">
        {{-- Profile Kecamatan --}}
        <div class="col-12 col-xl-4">
            <div class="panel h-100 text-center profile-card">
                <div class="profile-cover">
                    <img src="{{ asset('img/damkar1.jpg') }}" alt="Kecamatan cover preview">
                </div>
                <div class="profile-hero">
                    <div class="avatar-img avatar-xl profile-photo bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                        <i class="bi bi-building fs-1 text-primary" aria-hidden="true"></i>
                    </div>
                    <h2 class="h5 mb-1">{{ $kecamatan->nama }}</h2>
                    <p class="text-muted mb-3">Data Kecamatan</p>
                    <span class="badge text-bg-success">Active</span>
                </div>
                <div class="info-list mt-4 text-start">
                    <div>
                        <span>Nama Kecamatan</span>
                        <strong>{{ $kecamatan->nama }}</strong>
                    </div>
                    <div>
                        <span>Keterangan</span>
                        <strong>{{ $kecamatan->keterangan ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>Total Kelurahan</span>
                        <strong>{{ $kecamatan->kelurahans->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi & Daftar Kelurahan --}}
        <div class="col-12 col-xl-8">
            {{-- Informasi Waktu --}}
            <div class="panel mb-3">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-info-circle" aria-hidden="true"></i>
                            <span>Informasi lengkap data kecamatan.</span>
                        </h2>
                        <p class="text-muted mb-0">
                            Detail waktu pembuatan dan pembaruan data.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('kecamatans.edit', $kecamatan->id) }}">
                            <i class="bi bi-pencil-square" aria-hidden="true"></i>
                            Edit
                        </a>
                        <form action="{{ route('kecamatans.destroy', $kecamatan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kecamatan ini?')">
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
                            <p class="mb-1 fw-semibold">Data Kecamatan Dibuat</p>
                            <p class="text-muted small mb-0">
                                {{ $kecamatan->created_at->format('d M Y, H:i:s') }}
                                ({{ $kecamatan->created_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>
                    <div class="activity-item">
                        <span class="activity-dot bg-success"></span>
                        <div>
                            <p class="mb-1 fw-semibold">Terakhir Diperbarui</p>
                            <p class="text-muted small mb-0">
                                {{ $kecamatan->updated_at->format('d M Y, H:i:s') }}
                                ({{ $kecamatan->updated_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>
                    @if($kecamatan->created_at->eq($kecamatan->updated_at))
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

            {{-- Daftar Kelurahan --}}
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-list-ul" aria-hidden="true"></i>
                            <span>Daftar Kelurahan</span>
                        </h2>
                        <p class="text-muted mb-0">
                            List kelurahan yang berada di kecamatan <strong>{{ $kecamatan->nama }}</strong>.
                        </p>
                    </div>
                    <a href="{{ route('kelurahans.create') }}?kecamatan_id={{ $kecamatan->id }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle" aria-hidden="true"></i>
                        Tambah Kelurahan
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Kelurahan</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kecamatan->kelurahans as $index => $kelurahan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-img avatar-sm d-flex align-items-center justify-content-center bg-light">
                                            <i class="bi bi-building text-secondary"></i>
                                        </div>
                                        <div>
                                            <p class="fw-semibold mb-0">{{ $kelurahan->nama }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $kelurahan->keterangan ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a class="btn btn-light btn-sm text-info" href="{{ route('kelurahans.show', $kelurahan->id) }}" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a class="btn btn-light btn-sm text-primary" href="{{ route('kelurahans.edit', $kelurahan->id) }}" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('kelurahans.destroy', $kelurahan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelurahan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light btn-sm text-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-building fs-3 d-block mb-2"></i>
                                        Belum ada kelurahan di kecamatan ini.
                                        <a href="{{ route('kelurahans.create') }}?kecamatan_id={{ $kecamatan->id }}" class="d-block mt-2">
                                            <i class="bi bi-plus-circle" aria-hidden="true"></i>
                                            Tambah Kelurahan sekarang
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection