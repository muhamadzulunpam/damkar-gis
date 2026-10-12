@extends('layouts.admin')

@section('title', 'Detail Kejadian Penyelamatan')

@php
    // Format waktu ke H:i
    $fmtWaktu = fn ($t) => $t ? \Illuminate\Support\Carbon::parse($t)->format('H:i') : '-';
@endphp

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-shield-check" aria-hidden="true"></i>
            </span>
            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Detail Kejadian Penyelamatan</h1>
                <p class="text-muted mb-0">
                    Informasi lengkap data kejadian penyelamatan
                    <strong>{{ $kejadianPenyelamatan->id_penyelamatan }}</strong>.
                </p>
            </div>
        </div>
        <div class="heading-actions">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('kejadian-penyelamatans.index') }}">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Kembali
            </a>
            <a class="btn btn-primary btn-sm" href="{{ route('kejadian-penyelamatans.edit', $kejadianPenyelamatan) }}">
                <i class="bi bi-pencil" aria-hidden="true"></i>
                Edit Kejadian
            </a>
        </div>
    </div>

    <section class="row g-3">
        {{-- Profile Card --}}
        <div class="col-12 col-xl-4">
            <div class="panel h-100 text-center profile-card">
                <div class="profile-cover">
                    <img src="{{ asset('img/damkar1.jpg') }}" alt="Kejadian Penyelamatan cover preview">
                </div>
                <div class="profile-hero">
                    <div class="avatar-img avatar-xl profile-photo bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                        <i class="bi bi-shield-check fs-1 text-primary" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="info-list mt-4 text-start">
                    <div>
                        <span>ID Penyelamatan</span>
                        <strong>{{ $kejadianPenyelamatan->id_penyelamatan }}</strong>
                    </div>
                    <div>
                        <span>Jenis Kejadian</span>
                        <strong>{{ $kejadianPenyelamatan->jenis_kejadian ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>Kategori</span>
                        <strong>{{ $kejadianPenyelamatan->kategori_jenis ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>Kelompok</span>
                        <strong>
                            @if($kejadianPenyelamatan->kelompok_penyelamatan)
                                <span class="badge" style="background-color: #bfdbfe; color: #1e40af;">
                                    {{ $kejadianPenyelamatan->kelompok_penyelamatan }}
                                </span>
                            @else
                                -
                            @endif
                        </strong>
                    </div>
                    <div>
                        <span>Tanggal</span>
                        <strong>
                            {{ $kejadianPenyelamatan->tanggal
                                ? \Carbon\Carbon::parse($kejadianPenyelamatan->tanggal)->format('d M Y')
                                : '-' }}
                        </strong>
                    </div>
                    <div>
                        <span>Waktu Kejadian</span>
                        <strong>{{ $fmtWaktu($kejadianPenyelamatan->waktu_kejadian) }}</strong>
                    </div>
                    <div>
                        <span>Durasi</span>
                        <strong>
                            @if($kejadianPenyelamatan->durasi_menit)
                                {{ $kejadianPenyelamatan->durasi_menit }} menit
                            @else
                                -
                            @endif
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Panel --}}
        <div class="col-12 col-xl-8">
            {{-- Informasi Lokasi --}}
            <div class="panel mb-3">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-geo-alt" aria-hidden="true"></i>
                            <span>Informasi Lokasi</span>
                        </h2>
                        <p class="text-muted mb-0">
                            Detail lokasi kejadian penyelamatan.
                        </p>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase">Alamat Lengkap</label>
                        <p class="fw-semibold mb-0">{{ $kejadianPenyelamatan->alamat_lengkap ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase">Nama Pemilik</label>
                        <p class="fw-semibold mb-0">{{ $kejadianPenyelamatan->nama_pemilik ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase">Kecamatan</label>
                        <p class="fw-semibold mb-0">{{ $kejadianPenyelamatan->kecamatan->nama ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase">Kelurahan</label>
                        <p class="fw-semibold mb-0">{{ $kejadianPenyelamatan->kelurahan->nama ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Informasi Waktu --}}
            <div class="panel mb-3">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-clock-history" aria-hidden="true"></i>
                            <span>Informasi Waktu</span>
                        </h2>
                        <p class="text-muted mb-0">
                            Detail waktu kejadian dan penanganan.
                        </p>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="text-muted small fw-semibold text-uppercase">Tanggal</label>
                        <p class="fw-semibold mb-0">
                            {{ $kejadianPenyelamatan->tanggal
                                ? \Carbon\Carbon::parse($kejadianPenyelamatan->tanggal)->format('d M Y')
                                : '-' }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small fw-semibold text-uppercase">Waktu Kejadian</label>
                        <p class="fw-semibold mb-0">{{ $fmtWaktu($kejadianPenyelamatan->waktu_kejadian) }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small fw-semibold text-uppercase">Waktu Tiba</label>
                        <p class="fw-semibold mb-0">{{ $fmtWaktu($kejadianPenyelamatan->waktu_tiba) }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small fw-semibold text-uppercase">Waktu Selesai</label>
                        <p class="fw-semibold mb-0">{{ $fmtWaktu($kejadianPenyelamatan->waktu_selesai) }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small fw-semibold text-uppercase">Durasi</label>
                        <p class="fw-semibold mb-0">
                            @if($kejadianPenyelamatan->durasi_menit)
                                <span class="badge bg-primary">
                                    {{ $kejadianPenyelamatan->durasi_menit }} menit
                                </span>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Informasi Penanganan --}}
            <div class="panel mb-3">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-people" aria-hidden="true"></i>
                            <span>Informasi Penanganan</span>
                        </h2>
                        <p class="text-muted mb-0">
                            Detail regu, unit, dan penanganan kejadian.
                        </p>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold text-uppercase">Regu</label>
                        <p class="fw-semibold mb-0">
                            @if($kejadianPenyelamatan->regu)
                                <span class="badge" style="background-color: #bfdbfe; color: #1e40af;">
                                    <i class="bi bi-people-fill me-1"></i>
                                    {{ $kejadianPenyelamatan->regu->nama }}
                                </span>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold text-uppercase">Jumlah Unit</label>
                        <p class="fw-semibold mb-0">{{ $kejadianPenyelamatan->jumlah_unit ?? 0 }} unit</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold text-uppercase">Korban Jiwa</label>
                        <p class="fw-semibold mb-0">
                            @if($kejadianPenyelamatan->korban_jiwa)
                                <span class="badge" style="background-color: #fef3c7; color: #b45309;">
                                    {{ $kejadianPenyelamatan->korban_jiwa }} jiwa
                                </span>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-md-12">
                        <label class="text-muted small fw-semibold text-uppercase">Penyebab</label>
                        <p class="fw-semibold mb-0">{{ $kejadianPenyelamatan->penyebab ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Informasi Kerugian --}}
            <div class="panel mb-3">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-cash-coin" aria-hidden="true"></i>
                            <span>Informasi Kerugian</span>
                        </h2>
                        <p class="text-muted mb-0">
                            Detail kerugian dan luas area terdampak.
                        </p>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase">Luas Area</label>
                        <p class="fw-semibold mb-0">
                            @if($kejadianPenyelamatan->luas_area_m2)
                                {{ number_format($kejadianPenyelamatan->luas_area_m2, 2, ',', '.') }} m²
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase">Kerugian Harta</label>
                        <p class="fw-semibold mb-0">
                            Rp {{ number_format($kejadianPenyelamatan->kerugian_harta ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Keterangan & Aktivitas --}}
            <div class="panel mb-3">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-info-circle" aria-hidden="true"></i>
                            <span>Keterangan</span>
                        </h2>
                    </div>
                </div>
                <p class="mb-0">{{ $kejadianPenyelamatan->keterangan ?? '-' }}</p>
            </div>

            {{-- Activity --}}
            <div class="panel">
                <div class="activity-list">
                    <div class="activity-item">
                        <span class="activity-dot bg-primary"></span>
                        <div>
                            <p class="mb-1 fw-semibold">Data Dibuat</p>
                            <p class="text-muted small mb-0">
                                {{ $kejadianPenyelamatan->created_at->format('d M Y, H:i:s') }}
                                ({{ $kejadianPenyelamatan->created_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>
                    <div class="activity-item">
                        <span class="activity-dot bg-success"></span>
                        <div>
                            <p class="mb-1 fw-semibold">Terakhir Diperbarui</p>
                            <p class="text-muted small mb-0">
                                {{ $kejadianPenyelamatan->updated_at->format('d M Y, H:i:s') }}
                                ({{ $kejadianPenyelamatan->updated_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection