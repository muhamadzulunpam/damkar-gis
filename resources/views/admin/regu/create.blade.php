@extends('layouts.admin')

@section('title', 'Create Regu')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-plus-circle" aria-hidden="true"></i>
            </span>

            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Tambah Regu</h1>
                <p class="text-muted mb-0">
                    Tambahkan data regu baru ke dalam sistem.
                </p>
            </div>
        </div>

        <div class="heading-actions">
            <a href="{{ route('regus.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Kembali
            </a>
        </div>
    </div>

    <section class="row g-3">
        <div class="col-12">
            <form
                action="{{ route('regus.store') }}"
                method="POST"
                class="panel"
            >
                @csrf

                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-people" aria-hidden="true"></i>
                            <span>Informasi Regu</span>
                        </h2>

                        <p class="text-muted mb-0">
                            Lengkapi informasi data regus.
                        </p>
                    </div>
                </div>

                <div class="row g-3">

                    {{-- Nama Regu --}}
                    <div class="col-md-6">
                        <label for="nama" class="form-label">
                            Nama Regu <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}"
                            placeholder="Masukkan nama regu"
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
                    <div class="col-md-12">
                        <label for="keterangan" class="form-label">
                            Keterangan
                        </label>

                        <textarea
                            name="keterangan"
                            id="keterangan"
                            class="form-control @error('keterangan') is-invalid @enderror"
                            rows="3"
                            placeholder="Masukkan keterangan (opsional)"
                        >{{ old('keterangan') }}</textarea>

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
                        href="{{ route('regus.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                        Tambah Regu
                    </button>

                </div>
            </form>
        </div>
    </section>
</div>
@endsection