@extends('layouts.admin')

@section('title', 'Peta Kerawanan')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="mb-4">

        <h4 class="fw-bold mb-1">
            Peta Kerawanan
        </h4>

        <p class="text-muted mb-0">
            Visualisasi tingkat kerawanan wilayah berdasarkan
            hasil analisis K-Means (frekuensi kejadian dan
            kepadatan penduduk).
        </p>

    </div>


    {{-- ========================================================= --}}
    {{-- FILTER --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-funnel me-2"></i>
                Filter Peta
            </h6>
        </div>

        <div class="card-body">

            <form
                action="{{ route('peta-kerawanan.index') }}"
                method="GET"
            >

                <div class="row g-3">

                    {{-- Jenis --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Jenis Kejadian
                        </label>

                        <select
                            name="jenis"
                            class="form-select"
                            onchange="this.form.submit()"
                        >
                            <option value="penyelamatan"
                                @selected($jenis === 'penyelamatan')>
                                Penyelamatan
                            </option>

                            <option value="kebakaran"
                                @selected($jenis === 'kebakaran')>
                                Kebakaran
                            </option>
                        </select>

                    </div>


                    {{-- Periode --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Periode
                        </label>

                        <select name="periode" class="form-select">
                            @foreach($periodeList as $p)
                                <option value="{{ $p['value'] }}"
                                    @selected($periode === $p['value'])>
                                    {{ $p['label'] }}{{ $p['tersedia'] ? '' : ' - belum dianalisis' }}
                                </option>
                            @endforeach
                        </select>

                    </div>


                    {{-- Button --}}
                    <div class="col-md-4 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            <i class="bi bi-search me-2"></i>
                            Tampilkan Peta
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- STATUS K-MEANS --}}
    {{-- ========================================================= --}}

    @if($kmeansRun)

        <div class="alert alert-success border-0 shadow-sm">

            <div class="d-flex">

                <i class="bi bi-check-circle fs-4 me-3"></i>

                <div>

                    <strong>
                        Hasil K-Means tersedia
                    </strong>

                    <div class="small mt-1">

                        Analisis
                        <strong>{{ ucfirst($kmeansRun->jenis_kejadian) }}</strong>
                        periode
                        <strong>{{ $kmeansRun->rentang }}</strong>,
                        diproses oleh
                        <strong>{{ $kmeansRun->user?->name ?? '-' }}</strong>
                        pada
                        <strong>{{ $kmeansRun->created_at->format('d/m/Y H:i') }}</strong>.

                    </div>

                    <div class="small mt-1">

                        Silhouette:
                        <strong>{{ $kmeansRun->silhouette ?? '-' }}</strong>
                        &bull;
                        Davies-Bouldin:
                        <strong>{{ $kmeansRun->davies_bouldin ?? '-' }}</strong>

                    </div>

                    <div class="small mt-1 text-muted">

                        Tingkat kerawanan bersifat relatif terhadap
                        kelurahan lain pada periode yang dipilih.

                    </div>

                </div>

            </div>

        </div>

    @else

        <div class="alert alert-warning border-0 shadow-sm">

            <div class="d-flex align-items-start">

                <i class="bi bi-exclamation-triangle fs-4 me-3"></i>

                <div>

                    <strong>
                        Hasil K-Means belum tersedia
                    </strong>

                    <div class="mt-1">

                        Belum ada hasil analisis K-Means
                        untuk jenis
                        <strong>{{ ucfirst($jenis) }}</strong>.

                    </div>

                    @if(auth()->user()->role === 'admin')

                        <a href="{{ route('analisis-kmeans.index', ['jenis' => $jenis, 'periode' => $periode]) }}"
                        class="btn btn-warning btn-sm mt-3">
                            <i class="bi bi-cpu me-1"></i>
                            Jalankan K-Means
                        </a>

                    @endif

                </div>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- STATISTIK --}}
    {{-- ========================================================= --}}

    @if($kmeansRun)

        <div class="row g-3 mb-4">

            @foreach([
                ['rendah', 'Kerawanan Rendah', '#22c55e'],
                ['sedang', 'Kerawanan Sedang', '#eab308'],
                ['tinggi', 'Kerawanan Tinggi', '#ef4444'],
            ] as [$kunci, $label, $warna])

                <div class="col-md-4">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <div class="d-flex align-items-center">

                                <div
                                    class="rounded-circle me-3"
                                    style="width:45px; height:45px; background:{{ $warna }};"
                                ></div>

                                <div>

                                    <small class="text-muted">
                                        {{ $label }}
                                    </small>

                                    <h4 class="fw-bold mb-0">
                                        {{ $statistik[$kunci] }}
                                    </h4>

                                    <small class="text-muted">
                                        Kelurahan
                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- MAP --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h6 class="fw-bold mb-0">
                    <i class="bi bi-map me-2"></i>
                    Peta Kerawanan Kota Tangerang Selatan
                </h6>

                @if($kmeansRun)
                    <div class="small text-muted">
                        {{ ucfirst($jenis) }} &bull;
                        Periode: <strong>{{ $kmeansRun->rentang }}</strong>
                    </div>
                @endif

            </div>

        </div>

        <div class="card-body p-0">

            <div
                id="map"
                style="height:650px; width:100%; border-radius:0 0 8px 8px;"
            ></div>

        </div>

    </div>

</div>

@endsection


{{-- ============================================================= --}}
{{-- LEAFLET --}}
{{-- ============================================================= --}}

@push('styles')

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<style>

    .legend {
        background: white;
        padding: 12px;
        line-height: 20px;
        color: #333;
        border-radius: 6px;
        box-shadow: 0 1px 5px rgba(0,0,0,.25);
    }

    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }

    .legend-color {
        width: 18px;
        height: 18px;
        margin-right: 8px;
        border: 1px solid #999;
    }

</style>

@endpush


@push('scripts')

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

    /*
    |--------------------------------------------------------------------------
    | DATA DARI LARAVEL
    |--------------------------------------------------------------------------
    */

    const hasilKerawanan = @json($hasilKerawanan);

    const boundaryTangsel = @json($boundaryTangsel);


    /*
    |--------------------------------------------------------------------------
    | MAP
    |--------------------------------------------------------------------------
    */

    const map = L.map('map').setView([-6.295, 106.704], 12);

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }
    ).addTo(map);


    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    function normalisasiNama(nama) {
        return String(nama || '').trim().toLowerCase();
    }

    function cariKerawanan(namaKelurahan, namaKecamatan) {

        const key =
            normalisasiNama(namaKelurahan)
            + '|'
            + normalisasiNama(namaKecamatan);

        return hasilKerawanan[key] || null;
    }

    function formatAngka(nilai, desimal = 0) {
        return Number(nilai).toLocaleString('id-ID', {
            minimumFractionDigits: desimal,
            maximumFractionDigits: desimal
        });
    }


    /*
    |--------------------------------------------------------------------------
    | POLYGON KELURAHAN
    |--------------------------------------------------------------------------
    */

    const semuaPolygon = [];

    boundaryTangsel.forEach(function (wilayah) {

        if (!wilayah.path || wilayah.path.length === 0) {
            return;
        }

        const hasil = cariKerawanan(wilayah.nama, wilayah.kecamatan);

        const warna = hasil ? hasil.warna : '#adb5bd';
        const opacity = hasil ? 0.65 : 0.20;

        const polygon = L.polygon(
            wilayah.path,
            {
                color: '#ffffff',
                weight: 1,
                fillColor: warna,
                fillOpacity: opacity
            }
        ).addTo(map);

        semuaPolygon.push(polygon);


        /*
        |----------------------------------------------------------------------
        | Popup
        |----------------------------------------------------------------------
        */

        let popup = `
            <div style="min-width:240px">

                <h6 class="fw-bold mb-2">
                    ${wilayah.nama}
                </h6>

                <div class="small text-muted mb-2">
                    Kecamatan ${wilayah.kecamatan}
                </div>
        `;

        if (hasil) {

            popup += `

                <hr>

                <div class="mb-1">
                    <strong>Tingkat Kerawanan:</strong>
                </div>

                <div style="
                    display:inline-block;
                    padding:4px 10px;
                    border-radius:5px;
                    color:white;
                    background:${hasil.warna};
                ">
                    ${hasil.tingkat_kerawanan}
                </div>

                <hr>

                <div class="small">

                    <div class="fw-semibold mb-1">
                        Variabel K-Means
                    </div>

                    <div class="mb-1">
                        Frekuensi Kejadian:
                        <strong>${formatAngka(hasil.jumlah_kejadian)}</strong>
                    </div>

                    <div class="mb-1">
                        Kepadatan Penduduk:
                        <strong>${formatAngka(hasil.kepadatan_penduduk, 0)} jiwa/km²</strong>
                    </div>

                    ${hasil.silhouette_kelurahan !== null ? `
                        <div class="mb-2">
                            Silhouette:
                            <strong>${formatAngka(hasil.silhouette_kelurahan, 3)}</strong>
                        </div>
                    ` : '<div class="mb-2"></div>'}

                    <div class="fw-semibold mb-1 text-muted">
                        Informasi Pendukung
                    </div>

                    <div class="mb-1">
                        Korban Jiwa:
                        <strong>${formatAngka(hasil.jumlah_korban)}</strong>
                    </div>

                    <div>
                        Total Kerugian:
                        <strong>Rp ${formatAngka(hasil.total_kerugian)}</strong>
                    </div>

                </div>

            `;

        } else {

            popup += `

                <hr>

                <div class="alert alert-warning py-2 mb-0 small">
                    Belum terdapat hasil K-Means
                    untuk wilayah ini.
                </div>

            `;

        }

        popup += `</div>`;

        polygon.bindPopup(popup);


        /*
        |----------------------------------------------------------------------
        | Hover
        |----------------------------------------------------------------------
        */

        polygon.on('mouseover', function () {
            this.setStyle({
                weight: 2,
                color: '#333',
                fillOpacity: hasil ? 0.80 : 0.30
            });
        });

        polygon.on('mouseout', function () {
            this.setStyle({
                weight: 1,
                color: '#ffffff',
                fillOpacity: hasil ? 0.65 : 0.20
            });
        });

    });


    /*
    |--------------------------------------------------------------------------
    | LEGEND
    |--------------------------------------------------------------------------
    */

    const legend = L.control({ position: 'bottomright' });

    legend.onAdd = function () {

        const div = L.DomUtil.create('div', 'legend');

        div.innerHTML = `

            <strong>Tingkat Kerawanan</strong>

            <hr style="margin:6px 0">

            <div class="legend-item">
                <span class="legend-color" style="background:#22c55e"></span>
                Rendah
            </div>

            <div class="legend-item">
                <span class="legend-color" style="background:#eab308"></span>
                Sedang
            </div>

            <div class="legend-item">
                <span class="legend-color" style="background:#ef4444"></span>
                Tinggi
            </div>

            <div class="legend-item">
                <span class="legend-color" style="background:#adb5bd"></span>
                Belum dianalisis
            </div>

        `;

        return div;
    };

    legend.addTo(map);


    /*
    |--------------------------------------------------------------------------
    | FIT MAP
    |--------------------------------------------------------------------------
    */

    if (semuaPolygon.length > 0) {

        const group = L.featureGroup(semuaPolygon);

        map.fitBounds(group.getBounds(), { padding: [20, 20] });

    }

</script>

@endpush