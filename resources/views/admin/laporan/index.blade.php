@extends('layouts.admin')

@section('title', 'Laporan Tingkat Kerawanan')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">Laporan Tingkat Kerawanan</h1>
            <p class="text-muted mb-0">Ringkasan hasil analisis K-Means Kebakaran dan Penyelamatan.</p>
        </div>
    </div>

    {{-- Alert Error --}}
    @if (session('error'))
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Filter Periode --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 align-items-end">
                <div class="col-md-6 col-lg-4">
                    <label for="periode" class="form-label fw-semibold small text-uppercase text-muted">Periode Analisis</label>
                    <select name="periode" id="periode" class="form-select" required>
                        <option value="">— Pilih periode —</option>
                        @foreach ($opsi as $item)
                            <option value="{{ $item['value'] }}" @selected($periode === $item['value'])>
                                {{ $item['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-search me-1"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if (empty($opsi) || $opsi->isEmpty())
        <div class="alert alert-info border-0 shadow-sm">
            <i class="bi bi-info-circle-fill me-2"></i>
            Belum tersedia opsi periode. Pastikan data penduduk dan hasil analisis K-Means sudah tersedia.
        </div>
    @elseif (!$laporan || !$laporan['ada_data'])
        <div class="alert alert-info border-0 shadow-sm">
            <i class="bi bi-info-circle-fill me-2"></i>
            Belum ada hasil K-Means untuk periode yang dipilih. Jalankan analisis terlebih dahulu dari menu Analisis K-Means.
        </div>
    @else
        {{-- Info Periode & Tombol Unduh --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="small text-uppercase text-muted fw-semibold mb-1">Periode Aktif</div>
                    <h2 class="h5 mb-1 fw-bold">{{ $laporan['label'] }}</h2>
                    <div class="small text-muted">
                        <i class="bi bi-person-circle me-1"></i>{{ $laporan['dicetak_oleh'] }}
                        <span class="mx-2">·</span>
                        <i class="bi bi-clock me-1"></i>{{ $laporan['dicetak_pada']->format('d M Y, H:i') }}
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('laporan.pdf', ['periode' => $laporan['periode']]) }}" class="btn btn-outline-danger">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Unduh PDF
                    </a>
                    <a href="{{ route('laporan.excel', ['periode' => $laporan['periode']]) }}" class="btn btn-outline-success">
                        <i class="bi bi-file-earmark-excel me-1"></i> Unduh Excel
                    </a>
                </div>
            </div>
        </div>

        {{-- Tab Navigation --}}
        @php
            $jenisList = collect($laporan['jenis']);
            $firstKey = $jenisList->keys()->first();
        @endphp

        <ul class="nav nav-pills nav-fill gap-2 mb-4 p-2 bg-white rounded-3 shadow-sm" id="laporanTab" role="tablist">
            @foreach ($laporan['jenis'] as $key => $data)
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold {{ $loop->first ? 'active' : '' }}"
                            id="tab-{{ $key }}-btn"
                            data-bs-toggle="pill"
                            data-bs-target="#tab-{{ $key }}"
                            type="button" role="tab"
                            aria-controls="tab-{{ $key }}"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        <i class="bi {{ str_contains(strtolower($data['nama']), 'kebakaran') ? 'bi-fire' : 'bi-shield-check' }} me-2"></i>
                        {{ $data['nama'] }}
                        @if ($data['run'])
                            <span class="badge rounded-pill text-bg-light ms-2">{{ $data['ringkasan']['jumlah_kelurahan'] }}</span>
                        @endif
                    </button>
                </li>
            @endforeach
        </ul>

        {{-- Tab Content --}}
        <div class="tab-content" id="laporanTabContent">
            @foreach ($laporan['jenis'] as $key => $data)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                     id="tab-{{ $key }}" role="tabpanel"
                     aria-labelledby="tab-{{ $key }}-btn" tabindex="0">

                    @if (!$data['run'])
                        <div class="alert alert-secondary border-0 shadow-sm">
                            <i class="bi bi-inbox me-2"></i>
                            Belum ada hasil analisis untuk jenis ini pada periode tersebut.
                        </div>
                    @else
                        {{-- Kartu Ringkasan Statistik --}}
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-lg-3">
                                <div class="card border-0 shadow-sm h-100 stat-card stat-card-primary">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="stat-icon bg-primary-subtle text-primary">
                                                <i class="bi bi-geo-alt-fill"></i>
                                            </div>
                                        </div>
                                        <div class="small text-muted fw-semibold text-uppercase">Kelurahan</div>
                                        <div class="fs-3 fw-bold">{{ number_format($data['ringkasan']['jumlah_kelurahan']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="card border-0 shadow-sm h-100 stat-card stat-card-info">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="stat-icon bg-info-subtle text-info">
                                                <i class="bi bi-clipboard-data-fill"></i>
                                            </div>
                                        </div>
                                        <div class="small text-muted fw-semibold text-uppercase">Total Kejadian</div>
                                        <div class="fs-3 fw-bold">{{ number_format($data['ringkasan']['total_kejadian']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 col-lg-2">
                                <div class="card border-0 shadow-sm h-100 stat-card stat-card-danger">
                                    <div class="card-body">
                                        <div class="stat-icon bg-danger-subtle text-danger mb-2">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                        </div>
                                        <div class="small text-muted fw-semibold text-uppercase">Tinggi</div>
                                        <div class="fs-4 fw-bold text-danger">{{ number_format($data['ringkasan']['tinggi']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 col-lg-2">
                                <div class="card border-0 shadow-sm h-100 stat-card stat-card-warning">
                                    <div class="card-body">
                                        <div class="stat-icon bg-warning-subtle text-warning mb-2">
                                            <i class="bi bi-dash-circle-fill"></i>
                                        </div>
                                        <div class="small text-muted fw-semibold text-uppercase">Sedang</div>
                                        <div class="fs-4 fw-bold text-warning">{{ number_format($data['ringkasan']['sedang']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 col-lg-2">
                                <div class="card border-0 shadow-sm h-100 stat-card stat-card-success">
                                    <div class="card-body">
                                        <div class="stat-icon bg-success-subtle text-success mb-2">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </div>
                                        <div class="small text-muted fw-semibold text-uppercase">Rendah</div>
                                        <div class="fs-4 fw-bold text-success">{{ number_format($data['ringkasan']['rendah']) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kelurahan Kerawanan Tinggi --}}
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                <h3 class="h6 mb-0 fw-bold">Kelurahan dengan Kerawanan Tinggi</h3>
                                @if (!$data['tinggi']->isEmpty())
                                    <span class="badge text-bg-danger ms-2">{{ $data['tinggi']->count() }}</span>
                                @endif
                            </div>
                            <div class="card-body p-0">
                                @if ($data['tinggi']->isEmpty())
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-emoji-smile fs-3 d-block mb-2"></i>
                                        Tidak ada kelurahan dalam kategori Tinggi pada hasil ini.
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-3">Kecamatan</th>
                                                    <th>Kelurahan</th>
                                                    <th class="text-end">Kejadian</th>
                                                    <th class="text-end pe-3">Kepadatan/km²</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($data['tinggi'] as $baris)
                                                <tr>
                                                    <td class="ps-3">{{ $baris->kelurahan?->kecamatan?->nama ?? '-' }}</td>
                                                    <td class="fw-semibold">{{ $baris->kelurahan?->nama ?? '-' }}</td>
                                                    <td class="text-end">
                                                        <span class="badge text-bg-danger-subtle text-danger fw-semibold">
                                                            {{ number_format((int) $baris->jumlah_kejadian) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end pe-3">
                                                        {{ is_numeric($baris->kepadatan_penduduk ?? null) ? number_format((float) $baris->kepadatan_penduduk, 2, ',', '.') : '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Tabel Lengkap --}}
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                                <i class="bi bi-table text-primary me-2"></i>
                                <h3 class="h6 mb-0 fw-bold">Daftar Lengkap — {{ $data['nama'] }}</h3>
                                <span class="badge text-bg-light ms-auto">{{ $data['baris']->count() }} data</span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3" style="width: 60px;">No.</th>
                                                <th>Kecamatan</th>
                                                <th>Kelurahan</th>
                                                <th class="text-end">Jumlah Kejadian</th>
                                                <th class="text-end">Kepadatan/km²</th>
                                                <th class="pe-3">Tingkat Kerawanan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($data['baris'] as $baris)
                                            @php($badge = ['Tinggi' => 'danger', 'Sedang' => 'warning', 'Rendah' => 'success'][$baris->tingkat_kerawanan] ?? 'secondary')
                                            <tr>
                                                <td class="ps-3 text-muted">{{ $loop->iteration }}</td>
                                                <td>{{ $baris->kelurahan?->kecamatan?->nama ?? '-' }}</td>
                                                <td class="fw-semibold">{{ $baris->kelurahan?->nama ?? '-' }}</td>
                                                <td class="text-end">{{ number_format((int) $baris->jumlah_kejadian) }}</td>
                                                <td class="text-end">{{ is_numeric($baris->kepadatan_penduduk ?? null) ? number_format((float) $baris->kepadatan_penduduk, 2, ',', '.') : '-' }}</td>
                                                <td class="pe-3">
                                                    <span class="badge text-bg-{{ $badge }} px-3 py-2">
                                                        {{ $baris->tingkat_kerawanan ?? 'Tidak diketahui' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    Tidak ada detail hasil untuk ditampilkan.
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="alert alert-light border-0 shadow-sm small text-muted mb-0">
            <i class="bi bi-info-circle me-1"></i>
            <strong>Catatan:</strong> Tingkat kerawanan merupakan hasil pengelompokan relatif berdasarkan data dan periode yang dianalisis.
            Laporan ini membaca hasil K-Means yang sudah tersimpan dan tidak menjalankan ulang perhitungan.
        </div>
    @endif
</div>

{{-- Custom Styles --}}
@push('styles')
<style>
    .nav-pills .nav-link {
        color: #6c757d;
        border-radius: .5rem;
        transition: all .2s ease;
    }
    .nav-pills .nav-link:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }
    .nav-pills .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
        box-shadow: 0 4px 12px rgba(13, 110, 253, .3);
    }
    .stat-card {
        border-radius: .75rem;
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.08) !important;
    }
    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: .5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9ff;
    }
    .table thead th {
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .5px;
        font-weight: 600;
        color: #6c757d;
        border-bottom: 1px solid #e9ecef;
    }
    .badge.text-bg-danger-subtle {
        background-color: rgba(220, 53, 69, .12) !important;
    }
    /* Semua tab punya border tipis abu — selalu terlihat */
    #laporanTab .nav-link {
        border: 2px solid #e5e7eb;
        background: #f8f9fa;
        color: #495057;
        transition: all .2s ease;
    }

    /* Tab aktif: border biru tegas + background biru */
    #laporanTab .nav-link.active {
        border-color: #0d6efd;
        background-color: #0d6efd;
        color: #fff;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
    }

    /* Hover: border biru soft */
    #laporanTab .nav-link:hover:not(.active) {
        border-color: #0d6efd;
        background-color: #e7f1ff;
        color: #0d6efd;
    }

    /* Badge di tab aktif tetap kelihatan */
    #laporanTab .nav-link.active .badge {
        background-color: rgba(255, 255, 255, 0.9) !important;
        color: #0d6efd !important;
    }
</style>
@endpush
@endsection