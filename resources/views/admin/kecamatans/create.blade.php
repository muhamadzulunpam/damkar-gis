@extends('layouts.admin')

@section('title', 'Create Kecamatan')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-plus-circle" aria-hidden="true"></i>
            </span>

            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Tambah Kecamatan</h1>
                <p class="text-muted mb-0">
                    Tambahkan data kecamatan baru ke dalam sistem.
                </p>
            </div>
        </div>

        <div class="heading-actions">
            <a href="{{ route('kecamatans.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Kembali
            </a>
        </div>
    </div>

    <section class="row g-3">
        <div class="col-12">
            <form
                action="{{ route('kecamatans.store') }}"
                method="POST"
                class="panel"
            >
                @csrf

                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-geo-alt" aria-hidden="true"></i>
                            <span>Informasi Kecamatan</span>
                        </h2>

                        <p class="text-muted mb-0">
                            Lengkapi informasi data kecamatan.
                        </p>
                    </div>
                </div>

                <div class="row g-3">

                    {{-- Nama Kecamatan --}}
                    <div class="col-md-6">
                        <label for="nama" class="form-label">
                            Nama Kecamatan <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}"
                            placeholder="Masukkan nama kecamatan"
                            required
                        >

                        @error('nama')
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
                            placeholder="Masukkan keterangan (opsional)"
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
                        href="{{ route('kecamatans.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                        Tambah Kecamatan
                    </button>

                </div>
            </form>
        </div>
    </section>
</div>
@endsection