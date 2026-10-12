@extends('layouts.admin')

@section('title', 'Create Incident')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-plus-circle" aria-hidden="true"></i>
            </span>

            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Tambah Incident</h1>
                <p class="text-muted mb-0">
                    Tambahkan data incident baru ke dalam sistem. Pilih kelurahan, lalu atur posisi di peta jika diperlukan.
                </p>
            </div>
        </div>

        <div class="heading-actions">
            <a href="{{ route('incidents.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Kembali
            </a>
        </div>
    </div>

    <section class="row g-3">
        <div class="col-12">
            <form
                action="{{ route('incidents.store') }}"
                method="POST"
                class="panel"
                id="incidentForm"
            >
                @csrf

                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
                            <span>Informasi Incident</span>
                        </h2>

                        <p class="text-muted mb-0">
                            Lengkapi informasi data incident kejadian.
                        </p>
                    </div>
                </div>

                <div class="row g-3">

                    {{-- Kecamatan --}}
                    <div class="col-md-6">
                        <label for="kecamatan_id" class="form-label">
                            Kecamatan <span class="text-danger">*</span>
                        </label>

                        <select
                            name="kecamatan_id"
                            id="kecamatan_id"
                            class="form-select @error('kecamatan_id') is-invalid @enderror"
                            required
                        >
                            <option value="">Pilih Kecamatan</option>
                            @foreach($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                                    {{ $kecamatan->nama }}
                                </option>
                            @endforeach
                        </select>

                        @error('kecamatan_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Kelurahan --}}
                    <div class="col-md-6">
                        <label for="kelurahan_id" class="form-label">
                            Kelurahan <span class="text-danger">*</span>
                        </label>

                        <select
                            name="kelurahan_id"
                            id="kelurahan_id"
                            class="form-select"
                            required
                            disabled
                        >
                            <option value="">Pilih Kecamatan terlebih dahulu</option>

                            @foreach($kelurahans as $kelurahan)
                                <option
                                    value="{{ $kelurahan->id }}"
                                    data-kecamatan="{{ $kelurahan->kecamatan_id }}"
                                    data-lat="{{ $kelurahan->latitude }}"
                                    data-lng="{{ $kelurahan->longitude }}"
                                >
                                    {{ $kelurahan->nama }}
                                </option>
                            @endforeach
                        </select>

                        @error('kelurahan_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Jenis Kejadian --}}
                    <div class="col-md-6">
                        <label for="jenis_kejadian_id" class="form-label">
                            Jenis Kejadian <span class="text-danger">*</span>
                        </label>

                        <select
                            name="jenis_kejadian_id"
                            id="jenis_kejadian_id"
                            class="form-select @error('jenis_kejadian_id') is-invalid @enderror"
                            required
                        >
                            <option value="">Pilih Jenis Kejadian</option>
                            @foreach($jenisKejadians as $jenis)
                                <option value="{{ $jenis->id }}" {{ old('jenis_kejadian_id') == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->nama }}
                                </option>
                            @endforeach
                        </select>

                        @error('jenis_kejadian_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Kategori Kejadian --}}
                    <div class="col-md-6">          
                        <label for="kategori_kejadian_id" class="form-label">
                            Kategori Kejadian <span class="text-danger">*</span>
                        </label>

                        <select
                            name="kategori_kejadian_id"
                            id="kategori_kejadian_id"
                            class="form-select @error('kategori_kejadian_id') is-invalid @enderror"
                            required
                            disabled
                        >
                            <option value="">Pilih Jenis Kejadian terlebih dahulu</option>

                            @foreach($jenisKejadians as $jenis)
                                @foreach($jenis->kategoris as $kategori)
                                    <option
                                        value="{{ $kategori->id }}"
                                        data-jenis="{{ $jenis->id }}"
                                        {{ old('kategori_kejadian_id') == $kategori->id ? 'selected' : '' }}
                                    >
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>

                        @error('kategori_kejadian_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Tanggal Kejadian --}}
                    <div class="col-md-4">
                        <label for="tanggal_kejadian" class="form-label">
                            Tanggal Kejadian <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="tanggal_kejadian"
                            id="tanggal_kejadian"
                            class="form-control @error('tanggal_kejadian') is-invalid @enderror"
                            value="{{ old('tanggal_kejadian') }}"
                            required
                        >

                        @error('tanggal_kejadian')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Waktu Kejadian --}}
                    <div class="col-md-4">
                        <label for="waktu_kejadian" class="form-label">
                            Waktu Kejadian <span class="text-danger">*</span>
                        </label>

                        <input
                            type="time"
                            name="waktu_kejadian"
                            id="waktu_kejadian"
                            class="form-control @error('waktu_kejadian') is-invalid @enderror"
                            value="{{ old('waktu_kejadian') }}"
                            required
                        >

                        @error('waktu_kejadian')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4">
                        <label for="status" class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >
                            <option value="dilaporkan" {{ old('status') == 'dilaporkan' ? 'selected' : '' }}>Dilaporkan</option>
                            <option value="ditangani" {{ old('status') == 'ditangani' ? 'selected' : '' }}>Ditangani</option>
                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="col-md-12">
                        <label for="alamat" class="form-label">
                            Alamat <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="alamat"
                            id="alamat"
                            class="form-control @error('alamat') is-invalid @enderror"
                            value="{{ old('alamat') }}"
                            placeholder="Masukkan alamat kejadian"
                            required
                        >

                        @error('alamat')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-md-12">
                        <label for="deskripsi" class="form-label">
                            Deskripsi
                        </label>

                        <textarea
                            name="deskripsi"
                            id="deskripsi"
                            class="form-control @error('deskripsi') is-invalid @enderror"
                            rows="3"
                            placeholder="Masukkan deskripsi kejadian (opsional)"
                        >{{ old('deskripsi') }}</textarea>

                        @error('deskripsi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- MAP SECTION --}}
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title mb-2">
                                    <i class="bi bi-geo-alt text-primary"></i>
                                    Lokasi Kejadian
                                </h6>
                                <p class="text-muted small mb-3">
                                    Koordinat akan otomatis terisi dari kelurahan yang dipilih. 
                                    <span class="text-primary">Klik/geser marker pada peta</span> untuk memperakurat lokasi kejadian.
                                </p>

                                {{-- Map Container --}}
                                <div 
                                    id="map" 
                                    style="height: 400px; width: 100%; border-radius: 8px; background: #e9ecef;"
                                ></div>

                                {{-- Hidden inputs untuk koordinat --}}
                                <input 
                                    type="hidden" 
                                    name="latitude" 
                                    id="latitude" 
                                    value="{{ old('latitude') }}"
                                >
                                <input 
                                    type="hidden" 
                                    name="longitude" 
                                    id="longitude" 
                                    value="{{ old('longitude') }}"
                                >

                                {{-- Tampilkan koordinat saat ini --}}
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Latitude</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="bi bi-arrow-up text-success"></i>
                                            </span>
                                            <input 
                                                type="text" 
                                                id="latitude_display" 
                                                class="form-control bg-white" 
                                                value="{{ old('latitude', '-') }}" 
                                                readonly
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Longitude</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="bi bi-arrow-right text-danger"></i>
                                            </span>
                                            <input 
                                                type="text" 
                                                id="longitude_display" 
                                                class="form-control bg-white" 
                                                value="{{ old('longitude', '-') }}" 
                                                readonly
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Data Korban --}}
                    <div class="col-12 mt-3">
                        <h6 class="section-title mb-3">
                            <i class="bi bi-people" aria-hidden="true"></i>
                            <span>Data Korban</span>
                        </h6>
                    </div>

                    {{-- Jumlah Korban --}}
                    <div class="col-md-4">
                        <label for="jumlah_korban" class="form-label">
                            Jumlah Korban <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="jumlah_korban"
                            id="jumlah_korban"
                            class="form-control @error('jumlah_korban') is-invalid @enderror"
                            value="{{ old('jumlah_korban', 0) }}"
                            min="0"
                            required
                        >

                        @error('jumlah_korban')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Jumlah Meninggal --}}
                    <div class="col-md-4">
                        <label for="jumlah_meninggal" class="form-label">
                            Jumlah Meninggal <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="jumlah_meninggal"
                            id="jumlah_meninggal"
                            class="form-control @error('jumlah_meninggal') is-invalid @enderror"
                            value="{{ old('jumlah_meninggal', 0) }}"
                            min="0"
                            required
                        >

                        @error('jumlah_meninggal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Jumlah Luka --}}
                    <div class="col-md-4">
                        <label for="jumlah_luka" class="form-label">
                            Jumlah Luka <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="jumlah_luka"
                            id="jumlah_luka"
                            class="form-control @error('jumlah_luka') is-invalid @enderror"
                            value="{{ old('jumlah_luka', 0) }}"
                            min="0"
                            required
                        >

                        @error('jumlah_luka')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Estimasi Kerugian --}}
                    <div class="col-md-6">
                        <label for="estimasi_kerugian" class="form-label">
                            Estimasi Kerugian (Rp) <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            step="any"
                            name="estimasi_kerugian"
                            id="estimasi_kerugian"
                            class="form-control @error('estimasi_kerugian') is-invalid @enderror"
                            value="{{ old('estimasi_kerugian', 0) }}"
                            min="0"
                            placeholder="0"
                            required
                        >

                        @error('estimasi_kerugian')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">
                            Keterangan
                        </label>

                        <input
                            type="text"
                            name="keterangan"
                            id="keterangan"
                            class="form-control @error('keterangan') is-invalid @enderror"
                            value="{{ old('keterangan') }}"
                            placeholder="Masukkan keterangan tambahan (opsional)"
                        >

                        @error('keterangan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                {{-- Actions --}}
                <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">

                    <a
                        href="{{ route('incidents.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                        Tambah Incident
                    </button>

                </div>
            </form>
        </div>
    </section>
</div>

{{-- Leaflet CSS & JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    #map {
        cursor: pointer;
    }
    #map .leaflet-control-attribution {
        font-size: 9px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // =====================================================
        // 1. INISIALISASI MAP
        // =====================================================

        const defaultLat = {{ old('latitude', $defaultLat ?? -6.2) }};
        const defaultLng = {{ old('longitude', $defaultLng ?? 106.8) }};

        let map = L.map('map').setView(
            [defaultLat, defaultLng],
            13
        );

        // Tile OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);


        // =====================================================
        // 2. MARKER
        // =====================================================

        let marker = L.marker(
            [defaultLat, defaultLng],
            {
                draggable: true
            }
        ).addTo(map);


        // =====================================================
        // 3. ELEMENT FORM
        // =====================================================

        const kecamatanSelect = document.getElementById('kecamatan_id');
        const kelurahanSelect = document.getElementById('kelurahan_id');

        const jenisKejadianSelect = document.getElementById('jenis_kejadian_id');
        const kategoriSelect = document.getElementById('kategori_kejadian_id');

        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');

        const latitudeDisplay = document.getElementById('latitude_display');
        const longitudeDisplay = document.getElementById('longitude_display');


        // =====================================================
        // 4. UPDATE KOORDINAT
        // =====================================================

        function updateCoordinates(lat, lng) {

            latitudeInput.value = lat;
            longitudeInput.value = lng;

            latitudeDisplay.value = Number(lat).toFixed(7);
            longitudeDisplay.value = Number(lng).toFixed(7);
        }


        // =====================================================
        // 5. PINDAH MARKER KE LOKASI
        // =====================================================

        function moveToLocation(lat, lng) {

            if (
                lat === null ||
                lat === undefined ||
                lng === null ||
                lng === undefined ||
                lat === '' ||
                lng === ''
            ) {
                return;
            }

            const latNum = parseFloat(lat);
            const lngNum = parseFloat(lng);

            if (
                isNaN(latNum) ||
                isNaN(lngNum)
            ) {
                console.warn(
                    'Latitude atau longitude tidak valid:',
                    lat,
                    lng
                );

                return;
            }

            // Pindahkan peta
            map.setView(
                [latNum, lngNum],
                15
            );

            // Pindahkan marker
            marker.setLatLng(
                [latNum, lngNum]
            );

            // Update input koordinat
            updateCoordinates(
                latNum,
                lngNum
            );
        }


        // =====================================================
        // 6. KECAMATAN -> KELURAHAN
        // =====================================================

        if (kecamatanSelect && kelurahanSelect) {

            // Simpan semua option kelurahan
            const allKelurahanOptions = Array.from(
                kelurahanSelect.querySelectorAll('option')
            );

            kecamatanSelect.addEventListener('change', function () {

                const kecamatanId = this.value;

                // Kosongkan dropdown kelurahan
                kelurahanSelect.innerHTML = '';


                // Jika kecamatan belum dipilih
                if (!kecamatanId) {

                    kelurahanSelect.disabled = true;

                    const option = document.createElement('option');

                    option.value = '';
                    option.textContent =
                        'Pilih Kecamatan terlebih dahulu';

                    kelurahanSelect.appendChild(option);

                    return;
                }


                // Option default
                const defaultOption =
                    document.createElement('option');

                defaultOption.value = '';
                defaultOption.textContent =
                    'Pilih Kelurahan';

                kelurahanSelect.appendChild(
                    defaultOption
                );


                // Filter kelurahan berdasarkan kecamatan
                allKelurahanOptions.forEach(function (option) {

                    const optionKecamatan =
                        option.getAttribute(
                            'data-kecamatan'
                        );

                    if (
                        option.value &&
                        optionKecamatan === kecamatanId
                    ) {

                        kelurahanSelect.appendChild(
                            option.cloneNode(true)
                        );
                    }

                });


                // Aktifkan dropdown
                kelurahanSelect.disabled = false;

            });
        }


        // =====================================================
        // 7. KELURAHAN -> LOKASI PETA
        // =====================================================

        if (kelurahanSelect) {

            kelurahanSelect.addEventListener(
                'change',
                function () {

                    const selected =
                        this.options[
                            this.selectedIndex
                        ];

                    if (
                        !selected ||
                        !selected.value
                    ) {
                        return;
                    }

                    // Ambil koordinat
                    const lat =
                        selected.getAttribute(
                            'data-lat'
                        );

                    const lng =
                        selected.getAttribute(
                            'data-lng'
                        );


                    console.log(
                        'Kelurahan:',
                        selected.textContent.trim()
                    );

                    console.log(
                        'Latitude:',
                        lat
                    );

                    console.log(
                        'Longitude:',
                        lng
                    );


                    // Pindahkan peta
                    if (lat && lng) {

                        moveToLocation(
                            lat,
                            lng
                        );

                    } else {

                        console.warn(
                            'Koordinat kelurahan tidak ditemukan.'
                        );

                    }

                }
            );
        }


        // =====================================================
        // 8. JENIS KEJADIAN -> KATEGORI
        // =====================================================

        if (
            jenisKejadianSelect &&
            kategoriSelect
        ) {

            // Simpan semua option kategori
            const allKategoriOptions = Array.from(
                kategoriSelect.querySelectorAll('option')
            );


            jenisKejadianSelect.addEventListener(
                'change',
                function () {

                    const jenisId = this.value;

                    // Kosongkan kategori
                    kategoriSelect.innerHTML = '';


                    // Jika jenis belum dipilih
                    if (!jenisId) {

                        kategoriSelect.disabled = true;

                        const option =
                            document.createElement('option');

                        option.value = '';

                        option.textContent =
                            'Pilih Jenis Kejadian terlebih dahulu';

                        kategoriSelect.appendChild(
                            option
                        );

                        return;
                    }


                    // Option default
                    const defaultOption =
                        document.createElement('option');

                    defaultOption.value = '';

                    defaultOption.textContent =
                        'Pilih Kategori Kejadian';

                    kategoriSelect.appendChild(
                        defaultOption
                    );


                    // Filter kategori berdasarkan jenis kejadian
                    allKategoriOptions.forEach(
                        function (option) {

                            const optionJenis =
                                option.getAttribute(
                                    'data-jenis'
                                );

                            if (
                                option.value &&
                                optionJenis === jenisId
                            ) {

                                kategoriSelect.appendChild(
                                    option.cloneNode(true)
                                );
                            }

                        }
                    );


                    // Aktifkan kategori
                    kategoriSelect.disabled = false;

                }
            );
        }


        // =====================================================
        // 9. MARKER DRAG
        // =====================================================

        marker.on(
            'dragend',
            function () {

                const position =
                    marker.getLatLng();

                updateCoordinates(
                    position.lat,
                    position.lng
                );

            }
        );


        // =====================================================
        // 10. KLIK PADA PETA
        // =====================================================

        map.on(
            'click',
            function (e) {

                marker.setLatLng(
                    e.latlng
                );

                updateCoordinates(
                    e.latlng.lat,
                    e.latlng.lng
                );

            }
        );


        // =====================================================
        // 11. OLD VALUE KECAMATAN
        // =====================================================

        const oldKecamatan =
            "{{ old('kecamatan_id') }}";


        if (
            oldKecamatan &&
            kecamatanSelect
        ) {

            kecamatanSelect.value =
                oldKecamatan;

            // Jalankan filter kelurahan
            kecamatanSelect.dispatchEvent(
                new Event('change')
            );
        }


        // =====================================================
        // 12. OLD VALUE KELURAHAN
        // =====================================================

        const oldKelurahan =
            "{{ old('kelurahan_id') }}";


        if (
            oldKelurahan &&
            kelurahanSelect
        ) {

            // Tunggu dropdown selesai difilter
            setTimeout(function () {

                kelurahanSelect.value =
                    oldKelurahan;


                const selected =
                    kelurahanSelect.options[
                        kelurahanSelect.selectedIndex
                    ];


                if (
                    selected &&
                    selected.value
                ) {

                    const lat =
                        selected.getAttribute(
                            'data-lat'
                        );

                    const lng =
                        selected.getAttribute(
                            'data-lng'
                        );


                    if (
                        lat &&
                        lng
                    ) {

                        moveToLocation(
                            lat,
                            lng
                        );
                    }
                }

            }, 100);
        }


        // =====================================================
        // 13. OLD VALUE JENIS KEJADIAN
        // =====================================================

        const oldJenis =
            "{{ old('jenis_kejadian_id') }}";


        if (
            oldJenis &&
            jenisKejadianSelect
        ) {

            jenisKejadianSelect.value =
                oldJenis;

            // Jalankan filter kategori
            jenisKejadianSelect.dispatchEvent(
                new Event('change')
            );
        }


        // =====================================================
        // 14. OLD VALUE KATEGORI
        // =====================================================

        const oldKategori =
            "{{ old('kategori_kejadian_id') }}";


        if (
            oldKategori &&
            kategoriSelect
        ) {

            setTimeout(function () {

                kategoriSelect.value =
                    oldKategori;

            }, 100);
        }

    });
    </script>
@endsection