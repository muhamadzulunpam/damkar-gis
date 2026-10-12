@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    {{-- =========================
        HEADER
    ========================== --}}
    <div class="page-heading">

        <div class="page-heading-copy">

            <span class="page-icon">
                <i class="bi bi-speedometer2"></i>
            </span>

            <div>
                <p class="eyebrow mb-1">Overview</p>

                <h1 class="h3 mb-1">
                    {{ $title }}
                </h1>

                <p class="text-muted mb-0">
                    Monitoring data kejadian kebakaran dan penyelamatan
                    di Kota Tangerang Selatan.
                </p>
            </div>

        </div>

        <div class="heading-actions">

            <a href="{{ route('kejadian-kebakarans.create') }}"
               class="btn btn-primary btn-sm">

                <i class="bi bi-plus-circle"></i>
                Tambah Kebakaran

            </a>

            <a href="{{ route('kejadian-penyelamatans.create') }}"
               class="btn btn-outline-primary btn-sm">

                <i class="bi bi-plus-circle"></i>
                Tambah Penyelamatan

            </a>

        </div>

    </div>


    {{-- =========================
        STATISTIK
    ========================== --}}
    <section class="row g-3 mt-1"
             aria-label="Dashboard metrics">

        {{-- TOTAL KEJADIAN --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <article class="metric-card metric-primary">

                <div class="metric-top">

                    <span class="metric-label">
                        Total Kejadian
                    </span>

                    <span class="metric-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </span>

                </div>

                <div class="metric-value">
                    {{ number_format($totalKejadian) }}
                </div>

                <div class="metric-meta">
                    <span>
                        Kebakaran + Penyelamatan
                    </span>
                </div>

            </article>

        </div>


        {{-- KEBAKARAN --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <article class="metric-card metric-danger">

                <div class="metric-top">

                    <span class="metric-label">
                        Kejadian Kebakaran
                    </span>

                    <span class="metric-icon">
                        <i class="bi bi-fire"></i>
                    </span>

                </div>

                <div class="metric-value">
                    {{ number_format($totalKebakaran) }}
                </div>

                <div class="metric-meta">
                    <span>
                        Data kebakaran
                    </span>
                </div>

            </article>

        </div>


        {{-- PENYELAMATAN --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <article class="metric-card metric-success">

                <div class="metric-top">

                    <span class="metric-label">
                        Penyelamatan
                    </span>

                    <span class="metric-icon">
                        <i class="bi bi-shield-check"></i>
                    </span>

                </div>

                <div class="metric-value">
                    {{ number_format($totalPenyelamatan) }}
                </div>

                <div class="metric-meta">
                    <span>
                        Data penyelamatan
                    </span>
                </div>

            </article>

        </div>


        {{-- KORBAN JIWA --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <article class="metric-card metric-warning">

                <div class="metric-top">

                    <span class="metric-label">
                        Total Korban Jiwa
                    </span>

                    <span class="metric-icon">
                        <i class="bi bi-people"></i>
                    </span>

                </div>

                <div class="metric-value">
                    {{ number_format($totalKorban) }}
                </div>

                <div class="metric-meta">
                    <span>
                        Korban dari seluruh kejadian
                    </span>
                </div>

            </article>

        </div>

    </section>


    {{-- =========================
        GRAFIK KEJADIAN PER KELURAHAN
    ========================== --}}
    <section class="row g-3 mt-1" id="grafik">

        <div class="col-12">

            <div class="panel">

                <div class="panel-header">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 w-100">

                        <div>

                            <h2 class="h5 mb-1 section-title">
                                <i class="bi bi-bar-chart-line"></i>
                                <span>
                                    Grafik Kejadian per Kelurahan
                                </span>
                            </h2>

                            <p class="text-muted mb-0">
                                Jumlah kejadian di setiap kelurahan pada periode
                                <strong>{{ $labelPeriode }}</strong>
                                ({{ number_format(array_sum($jumlahPerKelurahan)) }}
                                kejadian).
                            </p>

                        </div>


                        {{-- Filter periode & jenis --}}
                        <form method="GET"
                              action="{{ url()->current() }}#grafik"
                              class="d-flex flex-wrap gap-2">

                            <select name="periode"
                                    class="form-select form-select-sm w-auto"
                                    onchange="this.form.submit()"
                                    aria-label="Periode">

                                @forelse($periodeList as $p)
                                    <option value="{{ $p['value'] }}"
                                        @selected($periode === $p['value'])>
                                        {{ $p['label'] }}
                                    </option>
                                @empty
                                    <option value="">
                                        Belum ada data
                                    </option>
                                @endforelse

                            </select>

                            <select name="jenis"
                                    class="form-select form-select-sm w-auto"
                                    onchange="this.form.submit()"
                                    aria-label="Jenis kejadian">

                                <option value="semua"
                                    @selected($jenis === 'semua')>
                                    Semua Jenis
                                </option>

                                <option value="kebakaran"
                                    @selected($jenis === 'kebakaran')>
                                    Kebakaran
                                </option>

                                <option value="penyelamatan"
                                    @selected($jenis === 'penyelamatan')>
                                    Penyelamatan
                                </option>

                            </select>

                        </form>

                    </div>

                </div>


                @if(array_sum($jumlahPerKelurahan) === 0)
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-info-circle fs-3"></i>
                        <p class="mb-0 mt-2">
                            Tidak ada kejadian pada periode {{ $labelPeriode }}
                            untuk jenis yang dipilih.
                        </p>
                    </div>
                @else

                    {{-- Dibungkus agar bisa digeser ke samping (banyak kelurahan) --}}
                    <div class="px-3 pb-3"
                         style="overflow-x: auto;">

                        <div style="
                                position: relative;
                                height: 440px;
                                min-width: {{ max(900, count($labelKelurahan) * 26) }}px;
                             ">
                            <canvas id="chartKejadian"></canvas>
                        </div>

                    </div>

                    <div class="px-3 pb-3 small text-muted">
                        Geser ke samping jika grafik terpotong. Arahkan kursor
                        ke batang untuk melihat kecamatan dan jumlahnya.
                    </div>

                @endif

            </div>

        </div>

    </section>


    <section class="row g-3 mt-1">

        {{-- =========================
            KEJADIAN PER KECAMATAN
        ========================== --}}
        <div class="col-12 col-xl-4">

            <div class="panel h-100">

                <div class="panel-header">

                    <div>

                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-geo-alt"></i>
                            <span>
                                Kejadian per Kecamatan
                            </span>
                        </h2>

                        <p class="text-muted mb-0">
                            Kecamatan dengan jumlah kejadian terbanyak
                            pada periode {{ $labelPeriode }}.
                        </p>

                    </div>

                </div>

                <div class="activity-list">

                    @forelse($kejadianPerKecamatan->take(7) as $kecamatan)

                        <div class="activity-item">

                            <span class="activity-dot bg-primary"></span>

                            <div class="flex-grow-1">

                                <p class="mb-1 fw-semibold">
                                    {{ $kecamatan->nama }}
                                </p>

                                <p class="text-muted small mb-0">
                                    {{ number_format($kecamatan->total_kejadian) }}
                                    kejadian
                                </p>

                            </div>

                        </div>

                    @empty

                        <div class="text-center text-muted py-4">

                            <i class="bi bi-info-circle fs-3"></i>

                            <p class="mb-0 mt-2">
                                Belum ada data kejadian.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- =========================
            KEJADIAN TERBARU
        ========================== --}}
        <div class="col-12 col-xl-8">

            <div class="panel h-100">

                <div class="panel-header">

                    <div>

                        <h2 class="h5 mb-1 section-title">

                            <i class="bi bi-clock-history"></i>

                            <span>
                                Kejadian Terbaru
                            </span>

                        </h2>

                        <p class="text-muted mb-0">
                            Data kejadian kebakaran dan penyelamatan terakhir.
                        </p>

                    </div>

                </div>


                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead>

                            <tr>

                                <th>No</th>

                                <th>Tanggal</th>

                                <th>Tipe</th>

                                <th>Jenis</th>

                                <th>Lokasi</th>

                                <th>Regu</th>

                                <th class="text-end">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($kejadianTerbaru as $index => $kejadian)

                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>

                                    <td>

                                        {{ \Carbon\Carbon::parse(
                                            $kejadian->tanggal
                                        )->format('d/m/Y') }}

                                    </td>


                                    <td>

                                        @if($kejadian->tipe === 'Kebakaran')

                                            <span class="badge text-bg-danger">
                                                <i class="bi bi-fire me-1"></i>
                                                Kebakaran
                                            </span>

                                        @else

                                            <span class="badge text-bg-success">
                                                <i class="bi bi-shield-check me-1"></i>
                                                Penyelamatan
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        {{ $kejadian->jenis_kejadian ?? '-' }}

                                    </td>


                                    <td>

                                        <div>

                                            <strong>
                                                {{ $kejadian->kelurahan->nama ?? '-' }}
                                            </strong>

                                            <div class="text-muted small">
                                                {{ $kejadian->kecamatan->nama ?? '-' }}
                                            </div>

                                        </div>

                                    </td>


                                    <td>

                                        @if($kejadian->regu)

                                            <span class="badge"
                                                  style="background-color: #bfdbfe; color: #1e40af;">

                                                <i class="bi bi-people-fill me-1"></i>
                                                {{ $kejadian->regu->nama }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td class="text-end">

                                        @if($kejadian->tipe === 'Kebakaran')

                                            <a href="{{ route('kejadian-kebakarans.show', $kejadian->id) }}"
                                               class="btn btn-light btn-sm">

                                                <i class="bi bi-eye"></i>

                                            </a>

                                        @else

                                            <a href="{{ route('kejadian-penyelamatans.show', $kejadian->id) }}"
                                               class="btn btn-light btn-sm">

                                                <i class="bi bi-eye"></i>

                                            </a>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7"
                                        class="text-center text-muted py-4">

                                        <i class="bi bi-database fs-3"></i>

                                        <div class="mt-2">
                                            Belum ada data kejadian.
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const ctx = document.getElementById('chartKejadian');

    // Canvas tidak ada kalau tidak ada data pada periode yang dipilih.
    if (!ctx) {
        return;
    }

    const labels    = @json($labelKelurahan);
    const data      = @json($jumlahPerKelurahan);
    const kecamatan = @json($kecamatanPerKelurahan);
    const jenis     = @json($jenis);
    const judul     = @json($judulGrafik);

    // Warna batang mengikuti jenis kejadian.
    const warna = {
        semua:        '37, 99, 235',
        kebakaran:    '220, 38, 38',
        penyelamatan: '22, 163, 74',
    };

    const rgb = warna[jenis] ?? warna.semua;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Kejadian',
                data: data,
                backgroundColor: `rgba(${rgb}, 0.75)`,
                hoverBackgroundColor: `rgba(${rgb}, 0.95)`,
                borderRadius: 4,
                borderSkipped: false,
                maxBarThickness: 28,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },

                // Judul grafik
                title: {
                    display: true,
                    text: judul,
                    font: { size: 15, weight: '600' },
                    padding: { bottom: 16 },
                },

                tooltip: {
                    callbacks: {
                        afterTitle: function (items) {
                            return 'Kecamatan ' + kecamatan[items[0].dataIndex];
                        },
                        label: function (context) {
                            return context.parsed.y + ' kejadian';
                        }
                    }
                }
            },
            scales: {
                // Sumbu Y: jumlah kejadian
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    title: { display: true, text: 'Jumlah Kejadian' },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },

                // Sumbu X: kelurahan (label dimiringkan)
                x: {
                    ticks: {
                        autoSkip: false,
                        minRotation: 60,
                        maxRotation: 90,
                        font: { size: 10 },
                    },
                    title: { display: true, text: 'Kelurahan' },
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endpush