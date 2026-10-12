@extends('layouts.admin')

@section('title', 'Analisis K-Means')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                Analisis K-Means
            </h4>

            <p class="text-muted mb-0">
                Analisis tingkat kerawanan wilayah berdasarkan
                frekuensi kejadian dan kepadatan penduduk.
            </p>
        </div>
    </div>


    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Validation --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>
                Terdapat kesalahan:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Filter & Proses --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-funnel me-2"></i>
                Parameter Analisis
            </h6>
        </div>

        <div class="card-body">

            <form
                action="{{ route('analisis-kmeans.proses') }}"
                method="POST"
                id="formKmeans"
            >
                @csrf

                <div class="row g-3">

                    {{-- Jenis Kejadian --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Jenis Kejadian
                        </label>

                        <select name="jenis" id="jenisKmeans" class="form-select" required>
                            <option value="penyelamatan"
                                @selected(old('jenis', $jenis) === 'penyelamatan')>
                                Penyelamatan
                            </option>

                            <option value="kebakaran"
                                @selected(old('jenis', $jenis) === 'kebakaran')>
                                Kebakaran
                            </option>
                        </select>
                    </div>


                    {{-- Periode --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Periode
                        </label>

                        <select name="periode" id="periodeKmeans" class="form-select" required>
                            @forelse($periodeOptions as $o)
                                <option value="{{ $o['value'] }}"
                                    data-label="{{ $o['label'] }}"
                                    @selected(old('periode', $periodeForm) === $o['value'])>
                                    {{ $o['label'] }}
                                </option>
                            @empty
                                <option value="">
                                    Data penduduk belum tersedia
                                </option>
                            @endforelse
                        </select>
                    </div>


                    {{-- Button --}}
                    <div class="col-md-4 d-flex align-items-end">

                        @if(auth()->user()->role === 'admin')

                            <button
                                type="button"
                                class="btn btn-primary w-100"
                                id="btnRunKmeans"
                            >
                                <i class="bi bi-cpu me-2"></i>
                                Proses K-Means
                            </button>

                        @else

                            <div class="alert alert-info w-100 mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Hanya Admin yang dapat
                                menjalankan proses K-Means.
                            </div>

                        @endif

                    </div>

                </div>

            </form>

        </div>
    </div>


    {{-- Informasi Alur --}}
    <div class="alert alert-info border-0 shadow-sm">

        <div class="d-flex">

            <i class="bi bi-info-circle fs-4 me-3"></i>

            <div>

                <strong>
                    Alur Analisis
                </strong>

                <div class="mt-1">

                    Admin memilih jenis kejadian dan periode,
                    kemudian menjalankan proses K-Means (k = 3) dengan
                    variabel <strong>frekuensi kejadian</strong> dan
                    <strong>kepadatan penduduk</strong>.
                    Hasil disimpan dan digunakan oleh modul
                    <strong>Peta Kerawanan</strong>.

                </div>

                <div class="mt-1 small">

                    Tingkat kerawanan bersifat relatif terhadap
                    kelurahan lain pada periode yang dipilih.

                </div>

            </div>

        </div>

    </div>


    {{-- Riwayat --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                <h6 class="fw-bold mb-0">

                    <i class="bi bi-clock-history me-2"></i>

                    Riwayat Proses K-Means

                </h6>

                {{-- Filter periode --}}
                <form method="GET"
                      action="{{ route('analisis-kmeans.index') }}"
                      class="d-flex gap-2">

                    <select name="filter_periode"
                            class="form-select form-select-sm w-auto">

                        <option value="">Semua Periode</option>

                        @foreach($filterOptions as $o)
                            <option value="{{ $o['value'] }}"
                                @selected($filterPeriode === $o['value'])>
                                {{ $o['label'] }}
                            </option>
                        @endforeach

                    </select>

                    <button type="submit"
                            class="btn btn-sm btn-primary">
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>

                    @if($filterPeriode)
                        <a href="{{ route('analisis-kmeans.index') }}"
                           class="btn btn-sm btn-outline-secondary">
                            Reset
                        </a>
                    @endif

                </form>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="text-center">
                                #
                            </th>

                            <th>
                                Jenis Kejadian
                            </th>

                            <th>
                                Periode
                            </th>

                            <th class="text-center">
                                Silhouette
                            </th>

                            <th class="text-center">
                                Davies-Bouldin
                            </th>

                            <th class="text-center">
                                Cluster
                            </th>

                            <th>
                                Diproses Oleh
                            </th>

                            <th>
                                Waktu Proses
                            </th>

                            <th class="text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($runs as $index => $run)

                            <tr>

                                <td class="text-center">
                                    {{ $runs->firstItem() + $index }}
                                </td>


                                <td>
                                    <span class="badge bg-primary">
                                        {{ ucfirst($run->jenis_kejadian) }}
                                    </span>
                                </td>


                                <td>
                                    <strong>
                                        {{ $run->rentang }}
                                    </strong>
                                </td>


                                <td class="text-center">
                                    {{ $run->silhouette ?? '-' }}
                                </td>


                                <td class="text-center">
                                    {{ $run->davies_bouldin ?? '-' }}
                                </td>


                                <td class="text-center">
                                    <span class="badge bg-dark">
                                        {{ $run->jumlah_cluster }}
                                        Cluster
                                    </span>
                                </td>


                                <td>
                                    {{ $run->user?->name ?? '-' }}
                                </td>


                                <td>
                                    {{ $run->created_at->format('d/m/Y H:i') }}
                                </td>


                                <td class="text-center">

                                    <a
                                        href="{{ route('analisis-kmeans.show', $run) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        <i class="bi bi-eye"></i>
                                        Detail
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-5 text-muted"
                                >

                                    <i
                                        class="bi bi-database-x fs-2 d-block mb-2"
                                    ></i>

                                    Belum ada proses K-Means.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($runs->hasPages())

            <div class="card-footer bg-white">

                {{ $runs->links() }}

            </div>

        @endif

    </div>

</div>
@push('scripts')
<script>
document.getElementById('btnRunKmeans')?.addEventListener('click', function () {
    const jenisSelect  = document.getElementById('jenisKmeans');
    const periodeSelect = document.getElementById('periodeKmeans');

    const jenis  = jenisSelect.options[jenisSelect.selectedIndex].text;
    const periode = periodeSelect.options[periodeSelect.selectedIndex].dataset.label
                 || periodeSelect.options[periodeSelect.selectedIndex].text;

    confirmAction({
        title:        'Jalankan K-Means',
        subtitle:     'Proses memakan waktu beberapa saat',
        message:      'Sistem akan mengelompokkan kelurahan berdasarkan frekuensi kejadian dan kepadatan penduduk. Lanjutkan?',
        detail: `
            <div class="d-flex flex-column gap-1">
                <span><i class="bi bi-tag-fill text-primary me-1"></i> Jenis: <strong>${jenis}</strong></span>
                <span><i class="bi bi-calendar-event text-primary me-1"></i> Periode: <strong>${periode}</strong></span>
                <span><i class="bi bi-diagram-3 text-primary me-1"></i> Metode: K-Means (k = 3)</span>
                <span><i class="bi bi-bar-chart text-primary me-1"></i> Variabel: frekuensi &amp; kepadatan</span>
            </div>
        `,
        confirmText:  'Ya, Jalankan',
        confirmClass: 'btn-primary',
        icon:         'bi-cpu-fill',
        iconColor:    'primary',
        onOk:         () => document.getElementById('formKmeans').submit()
    });
});
</script>
@endpush
@endsection