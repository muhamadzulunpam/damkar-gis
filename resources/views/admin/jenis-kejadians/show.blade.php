@extends('layouts.admin')

@section('title', 'Detail Jenis Kejadian')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-tags-fill" aria-hidden="true"></i>
            </span>
            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Detail Jenis Kejadian</h1>
                <p class="text-muted mb-0">
                    Informasi lengkap data jenis kejadian.
                </p>
            </div>
        </div>
        <div class="heading-actions">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('jenis-kejadians.index') }}">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Kembali
            </a>
            <a class="btn btn-primary btn-sm" href="{{ route('jenis-kejadians.edit', $jenisKejadian->id) }}">
                <i class="bi bi-pencil" aria-hidden="true"></i>
                Edit Jenis Kejadian
            </a>
        </div>
    </div>

    <section class="row g-3">
        <div class="col-12 col-xl-4">
            <div class="panel h-100 text-center profile-card">
                <div class="profile-cover">
                    <img src="{{ asset('img/damkar1.jpg') }}" alt="Jenis Kejadian cover preview">
                </div>
                <div class="profile-hero">
                    <div class="avatar-img avatar-xl profile-photo bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                        <i class="bi bi-tag fs-1 text-primary" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="info-list mt-4 text-start">
                    <div>
                        <span>Nama Jenis Kejadian</span>
                        <strong>{{ $jenisKejadian->nama }}</strong>
                    </div>
                    <div>
                        <span>Status</span>
                        <strong>
                            @if($jenisKejadian->aktif)
                                <span class="badge" style="background-color: #bbf7d0; color: #15803d;">
                                    Aktif
                                </span>
                            @else
                                <span class="badge" style="background-color: #fee2e2; color: #b91c1c;">
                                    Tidak Aktif
                                </span>
                            @endif

                        </strong>
                    </div>
                    <div class="d-block">
                        <span >Keterangan</span>
                        <strong class="d-block">{{ $jenisKejadian->keterangan ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>Jumlah Kategori</span>
                        <strong>{{ $jenisKejadian->kategoris->count() }}</strong>
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
                            <span>Informasi lengkap data jenis kejadian.</span>
                        </h2>
                        <p class="text-muted mb-0">
                            Detail waktu pembuatan dan pembaruan data.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('jenis-kejadians.edit', $jenisKejadian->id) }}">
                            <i class="bi bi-pencil-square" aria-hidden="true"></i>
                            Edit
                        </a>
                        <form action="{{ route('jenis-kejadians.destroy', $jenisKejadian->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis kejadian ini?')">
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
                            <p class="mb-1 fw-semibold">Data Jenis Kejadian Dibuat</p>
                            <p class="text-muted small mb-0">
                                {{ $jenisKejadian->created_at->format('d M Y, H:i:s') }}
                                ({{ $jenisKejadian->created_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>
                    <div class="activity-item">
                        <span class="activity-dot bg-success"></span>
                        <div>
                            <p class="mb-1 fw-semibold">Terakhir Diperbarui</p>
                            <p class="text-muted small mb-0">
                                {{ $jenisKejadian->updated_at->format('d M Y, H:i:s') }}
                                ({{ $jenisKejadian->updated_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>
                    @if($jenisKejadian->created_at->eq($jenisKejadian->updated_at))
                    <div class="activity-item">
                        <span class="activity-dot bg-warning"></span>
                        <div>
                            <p class="mb-1 fw-semibold">Status</p>
                            <p class="text-muted small mb-0">
                                <span class="badge" style="background-color: #fef3c7; color: #d97706;">
                                    <i class="bi bi-clock-history me-1"></i> Belum pernah diperbarui
                                </span>
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Daftar Kategori --}}
            @if($jenisKejadian->kategoris->count() > 0)
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-list" aria-hidden="true"></i>
                            <span>Daftar Kategori</span>
                        </h2>
                        <p class="text-muted mb-0">
                            Kategori yang termasuk dalam jenis kejadian ini.
                        </p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Kategori</th>
                                <th scope="col">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jenisKejadian->kategoris as $index => $kategori)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-img avatar-sm d-flex align-items-center justify-content-center bg-light">
                                            <i class="bi bi-tag text-secondary"></i>
                                        </div>
                                        <div>
                                            <p class="fw-semibold mb-0">{{ $kategori->nama }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $kategori->keterangan ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </section>
</div>
@endsection