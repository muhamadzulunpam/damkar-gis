@extends('layouts.admin')

@section('title', 'Create Kategori Kejadian')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-plus-circle" aria-hidden="true"></i>
            </span>

            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Tambah Kategori Kejadian</h1>
                <p class="text-muted mb-0">
                    Tambahkan data kategori kejadian baru ke dalam sistem.
                </p>
            </div>
        </div>

        <div class="heading-actions">
            <a href="{{ route('kategori-kejadians.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Kembali
            </a>
        </div>
    </div>

    <section class="row g-3">
        <div class="col-12">
            <form
                action="{{ route('kategori-kejadians.store') }}"
                method="POST"
                class="panel"
            >
                @csrf

                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-tags" aria-hidden="true"></i>
                            <span>Informasi Kategori Kejadian</span>
                        </h2>

                        <p class="text-muted mb-0">
                            Lengkapi informasi data kategori kejadian.
                        </p>
                    </div>
                </div>

                <div class="row g-3">

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

                            @foreach($jenisKejadians as $jenisKejadian)
                                <option
                                    value="{{ $jenisKejadian->id }}"
                                    {{ old('jenis_kejadian_id') == $jenisKejadian->id ? 'selected' : '' }}
                                >
                                    {{ $jenisKejadian->nama }}
                                </option>
                            @endforeach
                        </select>

                        @error('jenis_kejadian_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Nama Kategori --}}
                    <div class="col-md-6">
                        <label for="nama" class="form-label">
                            Nama Kategori <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}"
                            placeholder="Masukkan nama kategori"
                            required
                        >

                        @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Status Aktif --}}
                    <div class="col-md-6">
                        <label for="aktif" class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>

                        <select
                            name="aktif"
                            id="aktif"
                            class="form-select @error('aktif') is-invalid @enderror"
                            required
                        >
                            <option value="1" {{ old('aktif') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('aktif') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>

                        @error('aktif')
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
                        href="{{ route('kategori-kejadians.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                        Tambah Kategori Kejadian
                    </button>

                </div>
            </form>
        </div>
    </section>
</div>
@endsection