@extends('layouts.admin')

@section('title', 'Detail Hasil K-Means')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                Detail Hasil K-Means
            </h4>

            <p class="text-muted mb-0">
                {{ ucfirst($kmeansRun->jenis_kejadian) }}
                &bull; Tahun {{ $kmeansRun->rentang }}
            </p>
        </div>

        <a href="{{ route('analisis-kmeans.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>


    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Ringkasan --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Jenis Kejadian</div>
                    <div class="fs-5 fw-bold">
                        {{ ucfirst($kmeansRun->jenis_kejadian) }}
                    </div>
                    <div class="text-muted small mt-1">
                        Tahun {{ $kmeansRun->rentang }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Kejadian</div>
                    <div class="fs-5 fw-bold">
                        {{ number_format($totalKejadian, 0, ',', '.') }}
                    </div>
                    <div class="text-muted small mt-1">
                        {{ $jumlahKelurahan }} kelurahan
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Silhouette</div>
                    <div class="fs-5 fw-bold">
                        {{ $kmeansRun->silhouette ?? '-' }}
                    </div>
                    <div class="text-muted small mt-1">
                        Makin dekat 1 makin baik
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Davies-Bouldin</div>
                    <div class="fs-5 fw-bold">
                        {{ $kmeansRun->davies_bouldin ?? '-' }}
                    </div>
                    <div class="text-muted small mt-1">
                        Makin kecil makin baik
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="alert alert-info border-0 shadow-sm">
        <i class="bi bi-info-circle me-2"></i>
        Variabel: <strong>frekuensi kejadian</strong> dan
        <strong>kepadatan penduduk</strong> (k = {{ $kmeansRun->jumlah_cluster }}).
        Tingkat kerawanan bersifat <strong>relatif terhadap kelurahan lain
        pada periode yang dipilih</strong>.
        Diproses oleh {{ $kmeansRun->user?->name ?? '-' }}
        pada {{ $kmeansRun->created_at->format('d/m/Y H:i') }}.
    </div>


    {{-- Profil & Grafik --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-diagram-3 me-2"></i>
                        Profil Klaster
                    </h6>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tingkat</th>
                                    <th class="text-center">Kelurahan</th>
                                    <th class="text-end">Rata-rata Frekuensi</th>
                                    <th class="text-end">Rata-rata Kepadatan</th>
                                    <th class="text-end">Silhouette</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($profil as $p)
                                    <tr>
                                        <td>
                                            <span class="badge"
                                                  style="background-color: {{ $warnaTingkat[$p['tingkat']] }}">
                                                {{ $p['tingkat'] }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $p['jumlah'] }}</td>
                                        <td class="text-end">
                                            {{ number_format($p['frekuensi'], 2, ',', '.') }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($p['kepadatan'], 2, ',', '.') }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($p['silhouette'], 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-graph-up me-2"></i>
                        Sebaran Kelurahan
                    </h6>
                </div>

                <div class="card-body">
                    <canvas id="chartSebaran" height="220"></canvas>
                </div>

            </div>
        </div>

    </div>


    {{-- Hasil per kelurahan --}}
    <div class="card border-0 shadow-sm" id="hasil">

        <div class="card-header bg-white py-3">

            <h6 class="fw-bold mb-3">
                <i class="bi bi-table me-2"></i>
                Hasil per Kelurahan
            </h6>

            {{-- Filter --}}
            <form method="GET"
                  action="{{ route('analisis-kmeans.show', $kmeansRun) }}#hasil"
                  class="row g-2 align-items-end">

                <div class="col-md-5">
                    <label class="form-label small fw-semibold mb-1">
                        Cari Kelurahan / Kecamatan
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               class="form-control"
                               placeholder="Contoh: Pondok Benda">
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-semibold mb-1">
                        Status Kerawanan
                    </label>

                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach(['Tinggi', 'Sedang', 'Rendah'] as $s)
                            <option value="{{ $s }}"
                                @selected($status === $s)>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>
                </div>

                @if($search !== '' || $status)
                    <div class="col-md-auto">
                        <a href="{{ route('analisis-kmeans.show', $kmeansRun) }}#hasil"
                           class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset
                        </a>
                    </div>
                @endif

            </form>

        </div>

        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="60">No</th>
                            <th>Kelurahan</th>
                            <th>Kecamatan</th>
                            <th class="text-end">Frekuensi Kejadian</th>
                            <th class="text-end">Kepadatan (jiwa/km²)</th>
                            <th class="text-center">Tingkat</th>
                            <th class="text-end">Silhouette</th>
                            <th class="text-end">Korban Jiwa</th>
                            <th class="text-end">Total Kerugian</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($hasil as $i => $row)

                            <tr>

                                <td class="text-center">
                                    {{ $hasil->firstItem() + $i }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $row->kelurahan->nama ?? '-' }}
                                </td>

                                <td>
                                    {{ $row->kelurahan->kecamatan->nama ?? '-' }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($row->jumlah_kejadian, 0, ',', '.') }}
                                </td>

                                <td class="text-end">
                                    {{ number_format((float) $row->kepadatan_penduduk, 2, ',', '.') }}
                                </td>

                                <td class="text-center">
                                    <span class="badge"
                                          style="background-color: {{ $row->warna }}">
                                        {{ $row->tingkat_kerawanan }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    {{ $row->silhouette_kelurahan !== null
                                        ? number_format((float) $row->silhouette_kelurahan, 3, ',', '.')
                                        : '-' }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($row->jumlah_korban, 0, ',', '.') }}
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format((float) $row->total_kerugian, 0, ',', '.') }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="bi bi-database-x fs-2 d-block mb-2"></i>
                                    Tidak ada data yang cocok dengan filter.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

        <div class="card-footer bg-white">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        {{ $hasil->links('pagination::bootstrap-5') }}
                    </div>
            </div>

        </div>

    </div>

</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

<script>
    const dataset = @json($dataGrafik);

    new Chart(document.getElementById('chartSebaran'), {
        type: 'scatter',
        data: { datasets: dataset },
        options: {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (ctx) => {
                            const p = ctx.raw;
                            return p.nama + ': ' + p.x + ' kejadian, '
                                + Math.round(p.y).toLocaleString('id-ID') + ' jiwa/km²';
                        }
                    }
                }
            },
            scales: {
                x: { title: { display: true, text: 'Frekuensi kejadian' } },
                y: { title: { display: true, text: 'Kepadatan penduduk (jiwa/km²)' } }
            }
        }
    });
</script>

@endsection