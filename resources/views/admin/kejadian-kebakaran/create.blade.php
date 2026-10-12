@extends('layouts.admin')

@section('title', 'Create Kejadian Kebakaran')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-plus-circle" aria-hidden="true"></i>
            </span>

            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Tambah Kejadian Kebakaran</h1>
                <p class="text-muted mb-0">
                    Tambahkan data kejadian kebakaran baru ke dalam sistem.
                </p>
            </div>
        </div>

        <div class="heading-actions">
            <a href="{{ route('kejadian-kebakarans.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Kembali
            </a>
        </div>
    </div>

    <section class="row g-3">
        <div class="col-12">
            <form
                action="{{ route('kejadian-kebakarans.store') }}"
                method="POST"
                class="panel"
            >
                @csrf
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-fire" aria-hidden="true"></i>
                            <span>Informasi Kejadian Kebakaran</span>
                        </h2>

                        <p class="text-muted mb-0">
                            Lengkapi informasi data kejadian kebakaran.
                        </p>
                    </div>
                </div>

                <div class="row g-3">

                    {{-- ID Kejadian --}}
                    <div class="col-md-4">
                        <label for="id_kejadian" class="form-label">
                            ID Kejadian <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            id="id_kejadian_preview"
                            class="form-control bg-light"
                            value="{{ $previewId }}"
                            readonly
                        >
                    </div>

                    {{-- Kecamatan --}}
                    <div class="col-md-4">
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
                                <option
                                    value="{{ $kecamatan->id }}"
                                    {{ old('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}
                                >
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
                    <div class="col-md-4">
                        <label for="kelurahan_id" class="form-label">
                            Kelurahan <span class="text-danger">*</span>
                        </label>

                        <select
                            name="kelurahan_id"
                            id="kelurahan_id"
                            class="form-select @error('kelurahan_id') is-invalid @enderror"
                            required
                            disabled
                        >
                            <option value="">Pilih Kecamatan terlebih dahulu</option>

                            @foreach($kecamatans as $kecamatan)
                                @foreach($kecamatan->kelurahans as $kelurahan)
                                    <option
                                        value="{{ $kelurahan->id }}"
                                        data-kecamatan="{{ $kecamatan->id }}"
                                        {{ old('kelurahan_id') == $kelurahan->id ? 'selected' : '' }}
                                    >
                                        {{ $kelurahan->nama }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>

                        @error('kelurahan_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Alamat Lengkap --}}
                    <div class="col-md-8">
                        <label for="alamat_lengkap" class="form-label">
                            Alamat Lengkap <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="alamat_lengkap"
                            id="alamat_lengkap"
                            class="form-control @error('alamat_lengkap') is-invalid @enderror"
                            value="{{ old('alamat_lengkap') }}"
                            placeholder="Masukkan alamat lengkap kejadian"
                            required
                        >

                        @error('alamat_lengkap')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Nama Pemilik --}}
                    <div class="col-md-4">
                        <label for="nama_pemilik" class="form-label">
                            Nama Pemilik
                        </label>

                        <input
                            type="text"
                            name="nama_pemilik"
                            id="nama_pemilik"
                            class="form-control @error('nama_pemilik') is-invalid @enderror"
                            value="{{ old('nama_pemilik') }}"
                            placeholder="Masukkan nama pemilik (opsional)"
                        >

                        @error('nama_pemilik')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Tanggal --}}
                    <div class="col-md-4">
                        <label for="tanggal" class="form-label">
                            Tanggal <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="tanggal"
                            id="tanggal"
                            class="form-control @error('tanggal') is-invalid @enderror"
                            value="{{ old('tanggal') }}"
                            required
                        >

                        @error('tanggal')
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

                    {{-- Waktu Tiba --}}
                    <div class="col-md-4">
                        <label for="waktu_tiba" class="form-label">
                            Waktu Tiba
                        </label>

                        <input
                            type="time"
                            name="waktu_tiba"
                            id="waktu_tiba"
                            class="form-control @error('waktu_tiba') is-invalid @enderror"
                            value="{{ old('waktu_tiba') }}"
                        >

                        @error('waktu_tiba')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Waktu Selesai --}}
                    <div class="col-md-4">
                        <label for="waktu_selesai" class="form-label">
                            Waktu Selesai
                        </label>

                        <input
                            type="time"
                            name="waktu_selesai"
                            id="waktu_selesai"
                            class="form-control @error('waktu_selesai') is-invalid @enderror"
                            value="{{ old('waktu_selesai') }}"
                        >

                        @error('waktu_selesai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Durasi Menit (Auto Calculate - Readonly) --}}
                    <div class="col-md-4">
                        <label for="durasi_menit_display" class="form-label">
                            Durasi (Menit)
                        </label>

                        <div class="input-group">
                            
                            <input
                                type="text"
                                id="durasi_menit_display"
                                class="form-control bg-light"
                                value="{{ old('durasi_menit', '-') }}"
                                readonly
                            >
                            <span class="input-group-text bg-light">menit</span>
                        </div>

                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i>
                            Dihitung otomatis dari selisih waktu tiba dan waktu kejadian
                        </small>
                    </div>

                    {{-- Jenis Kejadian --}}
                    <div class="col-md-6">
                        <label for="jenis_kejadian" class="form-label">
                            Jenis Kejadian <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="jenis_kejadian"
                            id="jenis_kejadian"
                            class="form-control @error('jenis_kejadian') is-invalid @enderror"
                            value="{{ old('jenis_kejadian') }}"
                            placeholder="Contoh: Kebakaran Rumah"
                            required
                        >

                        @error('jenis_kejadian')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Kategori Jenis --}}
                    <div class="col-md-6">
                        <label for="kategori_jenis" class="form-label">
                            Kategori Jenis <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="kategori_jenis"
                            id="kategori_jenis"
                            class="form-control @error('kategori_jenis') is-invalid @enderror"
                            value="{{ old('kategori_jenis') }}"
                            placeholder="Contoh: Bangunan"
                            required
                        >

                        @error('kategori_jenis')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Kelompok Kebakaran --}}
                    <div class="mb-3">
                        <label for="kelompok_kebakaran" class="form-label">
                            Kelompok Kebakaran
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="kelompok_kebakaran"
                            id="kelompok_kebakaran"
                            class="form-select @error('kelompok_kebakaran') is-invalid @enderror"
                            required
                        >
                            <option value="">-- Pilih Kelompok Kebakaran --</option>

                            <option value="Pemukiman / Properti"
                                {{ old('kelompok_kebakaran') == 'Pemukiman / Properti' ? 'selected' : '' }}>
                                Pemukiman / Properti
                            </option>

                            <option value="Komersial / Industri"
                                {{ old('kelompok_kebakaran') == 'Komersial / Industri' ? 'selected' : '' }}>
                                Komersial / Industri
                            </option>

                            <option value="Instalasi / Non-Bangunan"
                                {{ old('kelompok_kebakaran') == 'Instalasi / Non-Bangunan' ? 'selected' : '' }}>
                                Instalasi / Non-Bangunan
                            </option>

                            <option value="Kebakaran Lainnya"
                                {{ old('kelompok_kebakaran') == 'Kebakaran Lainnya' ? 'selected' : '' }}>
                                Kebakaran Lainnya
                            </option>
                        </select>

                        @error('kelompok_kebakaran')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Penyebab (Textarea) --}}
                    <div class="col-md-6">
                        <label for="penyebab" class="form-label">
                            Penyebab
                        </label>

                        <textarea
                            name="penyebab"
                            id="penyebab"
                            rows="3"
                            class="form-control @error('penyebab') is-invalid @enderror"
                            placeholder="Masukkan penyebab kebakaran (opsional)"
                        >{{ old('penyebab') }}</textarea>

                        @error('penyebab')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Kerugian Benda (Textarea) --}}
                    <div class="col-md-6">
                        <label for="kerugian_benda" class="form-label">
                            Kerugian Benda
                        </label>

                        <textarea
                            name="kerugian_benda"
                            id="kerugian_benda"
                            rows="3"
                            class="form-control @error('kerugian_benda') is-invalid @enderror"
                            placeholder="Contoh: 1 Unit Motor"
                        >{{ old('kerugian_benda') }}</textarea>

                        @error('kerugian_benda')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Luas Area --}}
                    <div class="col-md-4">
                        <label for="luas_area_m2" class="form-label">
                            Luas Area (m²)
                        </label>

                        <input
                            type="number"
                            step="any"
                            name="luas_area_m2"
                            id="luas_area_m2"
                            class="form-control @error('luas_area_m2') is-invalid @enderror"
                            value="{{ old('luas_area_m2') }}"
                            min="0"
                            placeholder="Contoh: 100"
                        >

                        @error('luas_area_m2')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Kerugian Harta --}}
                    <div class="col-md-4">
                        <label for="kerugian_harta" class="form-label">
                            Kerugian Harta (Rp)
                        </label>

                        <input
                            type="number"
                            step="any"
                            name="kerugian_harta"
                            id="kerugian_harta"
                            class="form-control @error('kerugian_harta') is-invalid @enderror"
                            value="{{ old('kerugian_harta') }}"
                            min="0"
                            placeholder="0"
                        >

                        @error('kerugian_harta')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Korban Jiwa --}}
                    <div class="col-md-4">
                        <label for="korban_jiwa" class="form-label">
                            Korban Jiwa
                        </label>

                        <input
                            type="number"
                            name="korban_jiwa"
                            id="korban_jiwa"
                            class="form-control @error('korban_jiwa') is-invalid @enderror"
                            value="{{ old('korban_jiwa') }}"
                            min="0"
                            placeholder="0"
                        >

                        @error('korban_jiwa')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Jumlah Unit --}}
                    <div class="col-md-4">
                        <label for="jumlah_unit" class="form-label">
                            Jumlah Unit <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="jumlah_unit"
                            id="jumlah_unit"
                            class="form-control @error('jumlah_unit') is-invalid @enderror"
                            value="{{ old('jumlah_unit', 0) }}"
                            min="0"
                            required
                        >

                        @error('jumlah_unit')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Regu --}}
                    <div class="col-md-4">
                        <label for="regu_id" class="form-label">
                            Regu
                        </label>

                        <select
                            name="regu_id"
                            id="regu_id"
                            class="form-select @error('regu_id') is-invalid @enderror"
                        >
                            <option value="">Pilih Regu</option>

                            @foreach($regus as $regu)
                                <option
                                    value="{{ $regu->id }}"
                                    {{ old('regu_id') == $regu->id ? 'selected' : '' }}
                                >
                                    {{ $regu->nama }}
                                </option>
                            @endforeach
                        </select>

                        @error('regu_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Keterangan Korban --}}
                    <div class="col-md-12">
                        <label for="keterangan_korban" class="form-label">
                            Keterangan Korban
                        </label>

                        <textarea
                            name="keterangan_korban"
                            id="keterangan_korban"
                            class="form-control @error('keterangan_korban') is-invalid @enderror"
                            rows="2"
                            placeholder="Masukkan keterangan korban (opsional)"
                        >{{ old('keterangan_korban') }}</textarea>

                        @error('keterangan_korban')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                {{-- Actions --}}
                <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">

                    <a
                        href="{{ route('kejadian-kebakarans.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                        Tambah Kejadian Kebakaran
                    </button>

                </div>
            </form>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // =====================================================
    // CASCADING DROPDOWN: KECAMATAN -> KELURAHAN
    // =====================================================

    const kecamatanSelect = document.getElementById('kecamatan_id');
    const kelurahanSelect = document.getElementById('kelurahan_id');

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
                option.textContent = 'Pilih Kecamatan terlebih dahulu';
                kelurahanSelect.appendChild(option);

                return;
            }

            // Option default
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Pilih Kelurahan';
            kelurahanSelect.appendChild(defaultOption);

            // Filter kelurahan berdasarkan kecamatan
            allKelurahanOptions.forEach(function (option) {
                const optionKecamatan = option.getAttribute('data-kecamatan');

                if (option.value && optionKecamatan === kecamatanId) {
                    kelurahanSelect.appendChild(option.cloneNode(true));
                }
            });

            // Aktifkan dropdown
            kelurahanSelect.disabled = false;
        });

        // Trigger change jika ada old value
        if (kecamatanSelect.value) {
            kecamatanSelect.dispatchEvent(new Event('change'));

            // Set ulang kelurahan jika ada old value
            const oldKelurahan = "{{ old('kelurahan_id') }}";
            if (oldKelurahan) {
                setTimeout(function() {
                    kelurahanSelect.value = oldKelurahan;
                }, 50);
            }
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const waktuKejadian = document.getElementById('waktu_kejadian');
    const waktuTiba = document.getElementById('waktu_tiba');
    const durasiDisplay = document.getElementById('durasi_menit_display');

    if (!waktuKejadian || !waktuTiba || !durasiDisplay) return;

    function hitungDurasi() {
        const kejadian = waktuKejadian.value;
        const tiba = waktuTiba.value;

        if (!kejadian || !tiba) {
            durasiDisplay.value = '-';
            return;
        }

        const [jamK, menitK] = kejadian.split(':').map(Number);
        const [jamT, menitT] = tiba.split(':').map(Number);

        let totalKejadian = jamK * 60 + menitK;
        let totalTiba = jamT * 60 + menitT;

        if (totalTiba < totalKejadian) {
            totalTiba += 24 * 60;
        }

        durasiDisplay.value = totalTiba - totalKejadian;
    }

    waktuKejadian.addEventListener('change', hitungDurasi);
    waktuKejadian.addEventListener('input', hitungDurasi);
    waktuTiba.addEventListener('change', hitungDurasi);
    waktuTiba.addEventListener('input', hitungDurasi);

    hitungDurasi();
});
</script>
@endsection