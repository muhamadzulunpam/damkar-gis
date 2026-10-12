@extends('layouts.admin')

@section('title', 'Data Penduduk')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    {{-- =========================
        HEADER
    ========================== --}}
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-people-fill" aria-hidden="true"></i>
            </span>
            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">{{ $title ?? 'Data Penduduk' }}</h1>
                <p class="text-muted mb-0">
                    Kelola data jumlah penduduk dan luas wilayah setiap kelurahan.
                </p>
            </div>
        </div>

        <div class="heading-actions">
            {{-- Download Template --}}
            <div class="dropdown">
                <button class="btn btn-outline-success btn-sm dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <i class="bi bi-download me-1"></i>
                    Download Template
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @foreach($tahunTemplate as $th)
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('penduduk.download-template', ['tahun' => $th]) }}">
                                <i class="bi bi-file-earmark-excel me-1 text-success"></i>
                                Template Tahun {{ $th }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Upload Data --}}
            <button type="button"
                    class="btn btn-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalUpload">
                <i class="bi bi-upload me-1"></i>
                Upload Data
            </button>
        </div>
    </div>

    {{-- =========================
        ALERT
    ========================== --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            <strong>Success:</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <strong>Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Import Errors --}}
    @if (session('import_errors'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <div class="d-flex">
                <i class="bi bi-x-octagon-fill me-2 fs-4"></i>
                <div class="flex-grow-1">
                    <strong>Import gagal. Tidak ada data yang disimpan.</strong>
                    <ul class="mb-0 mt-2 small">
                        @foreach (array_slice(session('import_errors'), 0, 20) as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    @if (count(session('import_errors')) > 20)
                        <small class="d-block mt-2 text-muted">
                            ... dan {{ count(session('import_errors')) - 20 }} kesalahan lainnya.
                        </small>
                    @endif
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Error Validasi File --}}
    @error('file')
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i>
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @enderror

    {{-- =========================
        FILTER
    ========================== --}}
    <section class="panel mt-3">
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-funnel" aria-hidden="true"></i>
                    <span>Filter Data</span>
                </h2>
                <p class="text-muted mb-0">
                    Filter data penduduk berdasarkan tahun.
                </p>
            </div>
        </div>

        <form method="GET" action="{{ route('penduduk.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="tahun" class="form-label fw-semibold">
                        Tahun
                    </label>
                    <select name="tahun" id="tahun" class="form-select form-select-sm">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunList as $item)
                            <option value="{{ $item }}"
                                {{ (string) $tahun === (string) $item ? 'selected' : '' }}>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search me-1"></i>
                        Filter
                    </button>
                </div>

                @if($tahun)
                    <div class="col-md-auto">
                        <a href="{{ route('penduduk.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset
                        </a>
                    </div>
                @endif
            </div>
        </form>
    </section>

    {{-- =========================
        TABLE
    ========================== --}}
    <section class="panel mt-3">
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-table" aria-hidden="true"></i>
                    <span>Daftar Data Penduduk</span>
                </h2>
                <p class="text-muted mb-0">
                    Data jumlah penduduk dan luas wilayah per kelurahan.
                </p>
            </div>
            <div class="text-muted small">
                Total: <strong>{{ $penduduks->total() }}</strong> data
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col" width="60">No</th>
                        <th scope="col">Kelurahan</th>
                        <th scope="col">Kecamatan</th>
                        <th scope="col">Tahun</th>
                        <th scope="col">Jumlah Penduduk</th>
                        <th scope="col">Luas Wilayah</th>
                        <th scope="col">Kepadatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penduduks as $index => $penduduk)
                        @php
                            // Kategori kepadatan
                            $kepadatan = $penduduk->kepadatan_penduduk ?? 0;

                            if ($kepadatan >= 15000) {
                                $badgeBg = '#fee2e2';
                                $badgeColor = '#b91c1c';
                                $badgeLabel = 'Sangat Padat';
                            } elseif ($kepadatan >= 10000) {
                                $badgeBg = '#fef3c7';
                                $badgeColor = '#b45309';
                                $badgeLabel = 'Padat';
                            } elseif ($kepadatan >= 5000) {
                                $badgeBg = '#dbeafe';
                                $badgeColor = '#1e40af';
                                $badgeLabel = 'Sedang';
                            } else {
                                $badgeBg = '#bbf7d0';
                                $badgeColor = '#15803d';
                                $badgeLabel = 'Rendah';
                            }
                        @endphp
                        <tr>
                            <td>{{ $penduduks->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-img avatar-sm d-flex align-items-center justify-content-center bg-light">
                                        <i class="bi bi-building text-secondary"></i>
                                    </div>
                                    <div>
                                        <p class="fw-semibold mb-0">{{ $penduduk->kelurahan->nama }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">
                                    {{ $penduduk->kelurahan->kecamatan->nama ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $penduduk->tahun }}
                                </span>
                            </td>
                            <td>
                                <strong>
                                    {{ number_format($penduduk->jumlah_penduduk, 0, ',', '.') }}
                                </strong>
                                <small class="text-muted">jiwa</small>
                            </td>
                            <td>
                                <strong>
                                    {{ number_format((float) $penduduk->luas_wilayah_km2, 4, ',', '.') }}
                                </strong>
                                <small class="text-muted">km²</small>
                            </td>
                            <td>
                                <div>
                                    <span class="fw-semibold">
                                        {{ number_format($kepadatan, 2, ',', '.') }}
                                    </span>
                                    <small class="text-muted">jiwa/km²</small>
                                    <br>
                                    <span class="badge"
                                          style="background-color: {{ $badgeBg }}; color: {{ $badgeColor }};">
                                        {{ $badgeLabel }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-database-x fs-1 d-block mb-2"></i>
                                    @if($tahun)
                                        Tidak ada data penduduk untuk tahun <strong>{{ $tahun }}</strong>.
                                    @else
                                        Belum ada data penduduk.
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($penduduks->hasPages())
            <div class="mb-0 mt-3">
                {{ $penduduks->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
</div>

{{-- =========================
    MODAL UPLOAD
========================== --}}
<div class="modal fade" id="modalUpload" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST"
              action="{{ route('penduduk.import') }}"
              enctype="multipart/form-data"
              class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-upload me-1"></i>
                    Upload Data Penduduk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                {{-- Tahun --}}
                <div class="mb-3">
                    <label for="tahun_upload" class="form-label fw-semibold">
                        Tahun <span class="text-danger">*</span>
                    </label>
                    <select name="tahun" id="tahun_upload" class="form-select" required>
                        @foreach($tahunTemplate as $th)
                            <option value="{{ $th }}">{{ $th }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i>
                        Harus sama dengan tahun pada template.
                    </div>
                </div>

                {{-- File --}}
                <div class="mb-3">
                    <label for="file_upload" class="form-label fw-semibold">
                        File Excel <span class="text-danger">*</span>
                    </label>
                    <input type="file"
                           name="file"
                           id="file_upload"
                           class="form-control"
                           accept=".xlsx,.xls"
                           required>
                    <div class="form-text">
                        Format: .xlsx atau .xls (maks. 5MB)
                    </div>
                </div>

                {{-- Info Template --}}
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Pastikan format file sesuai template.
                    <a href="{{ route('penduduk.download-template', ['tahun' => date('Y')]) }}"
                       class="alert-link">
                        Download template
                    </a>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-upload me-1"></i>
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Auto-show modal jika ada error upload --}}
@if ($errors->has('file') || session('import_errors'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = new bootstrap.Modal(document.getElementById('modalUpload'));
            modal.show();
        });
    </script>
@endif
@endsection