@extends('layouts.admin')

@section('title', 'Detail Kejadian Kebakaran')

@section('content')

@php
    // Format waktu ke H:i (aman untuk string "14:30:00" maupun objek Carbon)
    $fmtWaktu = fn ($t) => $t ? \Illuminate\Support\Carbon::parse($t)->format('H:i') : '-';

    // Format rupiah
    $rupiah = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');

    $totalKerugian = (float) $kejadianKebakaran->kerugian_harta + (float) $kejadianKebakaran->kerugian_benda;
    $adaKorban = (int) $kejadianKebakaran->korban_jiwa > 0;
@endphp

<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-fire" aria-hidden="true"></i>
            </span>
            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Detail Kejadian Kebakaran</h1>
                <p class="text-muted mb-0">
                    Informasi lengkap data kejadian kebakaran.
                </p>
            </div>
        </div>
        <div class="heading-actions">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('kejadian-kebakarans.index') }}">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Kembali
            </a>
            <a class="btn btn-primary btn-sm" href="{{ route('kejadian-kebakarans.edit', $kejadianKebakaran->id) }}">
                <i class="bi bi-pencil" aria-hidden="true"></i>
                Edit Kejadian
            </a>
        </div>
    </div>

    <section class="row g-3">
        {{-- ============ KIRI: RINGKASAN ============ --}}
        <div class="col-12 col-xl-4">
            <div class="panel h-100 text-center profile-card">
                <div class="profile-cover">
                    <img src="{{ asset('img/damkar1.jpg') }}" alt="Kejadian kebakaran cover preview">
                </div>
                <div class="profile-hero">
                    <div class="avatar-img avatar-xl profile-photo bg-danger bg-opacity-10 d-flex align-items-center justify-content-center">
                        <i class="bi bi-fire fs-1 text-danger" aria-hidden="true"></i>
                    </div>
                    <h2 class="h5 mb-1">{{ $kejadianKebakaran->id_kejadian }}</h2>
                    <p class="text-muted mb-3">
                        <span class="badge text-bg-info">{{ $kejadianKebakaran->kecamatan->nama ?? '-' }}</span>
                        <span class="badge text-bg-secondary">{{ $kejadianKebakaran->kategori_jenis }}</span>
                    </p>
                    @if($adaKorban)
                        <span class="badge text-bg-danger">Ada Korban Jiwa</span>
                    @else
                        <span class="badge text-bg-success">Tidak Ada Korban Jiwa</span>
                    @endif
                </div>
                <div class="info-list mt-4 text-start">
                    <div>
                        <span>ID Kejadian</span>
                        <strong>{{ $kejadianKebakaran->id_kejadian }}</strong>
                    </div>
                    <div>
                        <span>Tanggal</span>
                        <strong>{{ optional($kejadianKebakaran->tanggal)->format('d M Y') ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>Kecamatan</span>
                        <strong>{{ $kejadianKebakaran->kecamatan->nama ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>Kelurahan</span>
                        <strong>{{ $kejadianKebakaran->kelurahan->nama ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>Alamat Lengkap</span>
                        <strong>{{ $kejadianKebakaran->alamat_lengkap }}</strong>
                    </div>
                    <div>
                        <span>Nama Pemilik</span>
                        <strong>{{ $kejadianKebakaran->nama_pemilik ?: '-' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ KANAN: DETAIL ============ --}}
        <div class="col-12 col-xl-8">

            {{-- Detail Kejadian --}}
            <div class="panel mb-3">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-info-circle" aria-hidden="true"></i>
                            <span>Detail Kejadian</span>
                        </h2>
                        <p class="text-muted mb-0">
                            Jenis, penyebab, dan penanganan kejadian.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('kejadian-kebakarans.edit', $kejadianKebakaran->id) }}">
                            <i class="bi bi-pencil-square" aria-hidden="true"></i>
                            Edit
                        </a>
                        <form action="{{ route('kejadian-kebakarans.destroy', $kejadianKebakaran->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kejadian kebakaran ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash" aria-hidden="true"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-list">
                            <div>
                                <span>Jenis Kejadian</span>
                                <strong>{{ $kejadianKebakaran->jenis_kejadian }}</strong>
                            </div>
                            <div>
                                <span>Kategori Jenis</span>
                                <strong>{{ $kejadianKebakaran->kategori_jenis }}</strong>
                            </div>
                            <div>
                                <span>Penyebab</span>
                                <strong>{{ $kejadianKebakaran->penyebab ?: '-' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-list">
                            <!-- Kelompok Kebakaran -->
                            <div>
                                <span>Kelompok Kebakaran</span>
                                <strong>{{ $kejadianKebakaran->kelompok_kebakaran ?: '-' }}</strong>
                            </div>
                            <div>
                                <span>Luas Area</span>
                                <strong>
                                    {{ $kejadianKebakaran->luas_area_m2 !== null
                                        ? number_format((float) $kejadianKebakaran->luas_area_m2, 2, ',', '.') . ' m²'
                                        : '-' }}
                                </strong>
                            </div>
                            <div>
                                <span>Jumlah Unit</span>
                                <strong>{{ $kejadianKebakaran->jumlah_unit }} unit</strong>
                            </div>
                            <div>
                                <span>Regu</span>
                                @if($kejadianKebakaran->regu)
                                <strong>{{ $kejadianKebakaran->regu->nama }}</strong>
                                 @else
                                <strong>-</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kerugian & Korban --}}
            <div class="panel mb-3">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-cash-coin" aria-hidden="true"></i>
                            <span>Kerugian &amp; Korban</span>
                        </h2>
                        <p class="text-muted mb-0">
                            Estimasi kerugian dan informasi korban.
                        </p>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-list">
                            <div>
                                <span>Kerugian Harta</span>
                                <strong>{{ $rupiah($kejadianKebakaran->kerugian_harta) }}</strong>
                            </div>
                            <div>
                                <span>Kerugian Benda</span>
                                <strong>{{ $rupiah($kejadianKebakaran->kerugian_benda) }}</strong>
                            </div>
                            <div>
                                <span>Total Kerugian</span>
                                <strong class="text-danger">{{ $rupiah($totalKerugian) }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-list">
                            <div>
                                <span>Korban Jiwa</span>
                                <strong>{{ (int) $kejadianKebakaran->korban_jiwa }} orang</strong>
                            </div>
                            <div>
                                <span>Keterangan Korban</span>
                                <strong>{{ $kejadianKebakaran->keterangan_korban ?: '-' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kronologi Penanganan --}}
            <div class="panel mb-3">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-clock-history" aria-hidden="true"></i>
                            <span>Kronologi Penanganan</span>
                        </h2>
                        <p class="text-muted mb-0">
                            Waktu kejadian, kedatangan petugas, hingga penanganan selesai.
                        </p>
                    </div>
                </div>

                <div class="activity-list">
                    <div class="activity-item">
                        <span class="activity-dot bg-danger"></span>
                        <div>
                            <p class="mb-1 fw-semibold">Kejadian Terjadi</p>
                            <p class="text-muted small mb-0">
                                {{ optional($kejadianKebakaran->tanggal)->format('d M Y') }},
                                {{ $fmtWaktu($kejadianKebakaran->waktu_kejadian) }} WIB
                            </p>
                        </div>
                    </div>

                    <div class="activity-item">
                        <span class="activity-dot bg-primary"></span>
                        <div>
                            <p class="mb-1 fw-semibold">Petugas Tiba di Lokasi</p>
                            <p class="text-muted small mb-0">
                                @if($kejadianKebakaran->waktu_tiba)
                                    {{ $fmtWaktu($kejadianKebakaran->waktu_tiba) }} WIB
                                    @if($kejadianKebakaran->durasi_menit !== null)
                                        <span class="badge text-bg-info ms-1">
                                            Respon {{ $kejadianKebakaran->durasi_menit }} menit
                                        </span>
                                    @endif
                                @else
                                    <span class="badge text-bg-warning">Belum tercatat</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="activity-item">
                        <span class="activity-dot bg-success"></span>
                        <div>
                            <p class="mb-1 fw-semibold">Penanganan Selesai</p>
                            <p class="text-muted small mb-0">
                                @if($kejadianKebakaran->waktu_selesai)
                                    {{ $fmtWaktu($kejadianKebakaran->waktu_selesai) }} WIB
                                @else
                                    <span class="badge text-bg-warning">Belum tercatat</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Riwayat Data --}}
            <div class="panel mb-3">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-journal-text" aria-hidden="true"></i>
                            <span>Riwayat Data</span>
                        </h2>
                        <p class="text-muted mb-0">
                            Detail waktu pembuatan dan pembaruan data.
                        </p>
                    </div>
                </div>

                <div class="activity-list">
                    <div class="activity-item">
                        <span class="activity-dot bg-primary"></span>
                        <div>
                            <p class="mb-1 fw-semibold">Data Kejadian Dibuat</p>
                            <p class="text-muted small mb-0">
                                {{ $kejadianKebakaran->created_at->format('d M Y, H:i:s') }}
                                ({{ $kejadianKebakaran->created_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>
                    <div class="activity-item">
                        <span class="activity-dot bg-success"></span>
                        <div>
                            <p class="mb-1 fw-semibold">Terakhir Diperbarui</p>
                            <p class="text-muted small mb-0">
                                {{ $kejadianKebakaran->updated_at->format('d M Y, H:i:s') }}
                                ({{ $kejadianKebakaran->updated_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>
                    @if($kejadianKebakaran->created_at->eq($kejadianKebakaran->updated_at))
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