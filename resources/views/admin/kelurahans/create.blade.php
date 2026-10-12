@extends('layouts.admin')

@section('title', 'Create Kelurahan')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-plus-circle" aria-hidden="true"></i>
            </span>

            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Tambah Kelurahan</h1>
                <p class="text-muted mb-0">
                    Tambahkan data kelurahan baru ke dalam sistem.
                </p>
            </div>
        </div>

        <div class="heading-actions">
            <a href="{{ route('kelurahans.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Kembali
            </a>
        </div>
    </div>

    <section class="row g-3">
        <div class="col-12">
            <form
                action="{{ route('kelurahans.store') }}"
                method="POST"
                class="panel"
            >
                @csrf

                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-geo-alt" aria-hidden="true"></i>
                            <span>Informasi Kelurahan</span>
                        </h2>

                        <p class="text-muted mb-0">
                            Lengkapi informasi data kelurahan.
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

                    {{-- Nama Kelurahan --}}
                    <div class="col-md-6">
                        <label for="nama" class="form-label">
                            Nama Kelurahan <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}"
                            placeholder="Masukkan nama kelurahan"
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
                        href="{{ route('kelurahans.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                        Tambah Kelurahan
                    </button>

                </div>
            </form>
        </div>
    </section>
</div>
@endsection