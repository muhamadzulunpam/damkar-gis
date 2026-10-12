@extends('layouts.admin')

@section('title', 'Kelurahan')

@section('content')
        <div class="container-fluid px-3 px-lg-4 py-4">
          <div class="page-heading">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-geo-alt" aria-hidden="true"></i></span>
              <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">{{ $title }}</h1>
                <p class="text-muted mb-0">Kelola data kelurahan dan wilayah administratif.</p>
              </div>
            </div>
            <div class="heading-actions"> 
                <a href="{{ route('kelurahans.create') }}" class="btn btn-primary btn-sm py-2 px-3" > 
                    <i class="bi bi-plus-circle" aria-hidden="true"></i>
                    Tambah Kelurahan 
                </a> 
            </div>
          </div>

          <section class="panel mt-3">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success:</strong> {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"
                    ></button>
                </div>
            @endif
            <div class="panel-header">
              <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-geo-alt" aria-hidden="true"></i><span>Daftar Kelurahan</span></h2>
                <p class="text-muted mb-0">List semua kelurahan yang terdaftar dalam sistem.</p>
              </div>
              <div class="d-flex gap-2 w-100" style="max-width: 500px;">
                <!-- Form Pencarian -->
                <form action="{{ route('kelurahans.index') }}" method="GET" class="w-100" style="max-width: 300px;">
                  <div class="position-relative">
                      <i
                          class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"
                          aria-hidden="true"
                      ></i>

                      <input
                          type="search"
                          name="search"
                          class="form-control form-control-sm ps-5"
                          placeholder="Cari nama atau keterangan..."
                          value="{{ request('search') }}"
                          aria-label="Cari kelurahan"
                      >
                  </div>
                </form>

                <!-- Filter Kecamatan -->
                <form action="{{ route('kelurahans.index') }}" method="GET" class="w-100" style="max-width: 200px;">
                  @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                  @endif
                  <select
                      name="kecamatan_id"
                      class="form-select form-select-sm"
                      onchange="this.form.submit()"
                      aria-label="Filter kecamatan"
                  >
                      <option value="">Semua Kecamatan</option>
                      @foreach($kecamatans as $kecamatan)
                          <option value="{{ $kecamatan->id }}" {{ request('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                              {{ $kecamatan->nama }}
                          </option>
                      @endforeach
                  </select>
                </form>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Kelurahan</th>
                    <th scope="col">Kecamatan</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Dibuat</th>
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody> 
                    @forelse($kelurahans as $index => $kelurahan)
                    <tr>
                        <td>
                            {{ $kelurahans->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2"> 
                                <div class="avatar-img avatar-sm d-flex align-items-center justify-content-center bg-light"> 
                                    <i class="bi bi-building text-secondary"></i> 
                                </div> 
                                <div> 
                                    <p class="fw-semibold mb-0">{{ $kelurahan->nama }}</p> 
                                </div> 
                            </div> 
                        </td>
                        <td>
                            <span class="badge" style="background-color: #93c5fd; color: var(--admin-primary-dark);">
                                {{ $kelurahan->kecamatan->nama ?? '-' }}
                            </span>

                        </td>
                        <td>
                            {{ $kelurahan->keterangan ?? '-' }}
                        </td>
                        <td> 
                            {{ $kelurahan->created_at->format('d M Y') }} 
                        </td>
                        <td class="text-center"> 
                            <div class="d-flex justify-content-center gap-1">
                                <a class="btn btn-light btn-sm text-info" href="{{ route('kelurahans.show', $kelurahan->id) }}" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a class="btn btn-light btn-sm text-primary" href="{{ route('kelurahans.edit', $kelurahan->id) }}" title="Edit"> 
                                    <i class="bi bi-pencil-square"></i> 
                                </a>  
                                <form action="{{ route('kelurahans.destroy', $kelurahan->id) }}" 
                                    method="POST" 
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE') 
                                    <button type="button" 
                                            class="btn btn-light btn-sm text-danger btn-delete" 
                                            title="Hapus"
                                            data-nama="{{ $kelurahan->nama }}"
                                            data-title="Hapus Kelurahan">
                                        <i class="bi bi-trash"></i> 
                                    </button> 
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-geo-alt fs-3 d-block mb-2"></i>
                                @if(request('search') || request('kecamatan_id'))
                                    Tidak ada kelurahan yang sesuai dengan pencarian atau filter yang dipilih.
                                @else
                                    Belum ada kelurahan.
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
              </table>
            </div>
            <div class="mb-0 mt-3">
                {{ $kelurahans->links('pagination::bootstrap-5') }}
            </div>
          </section>
        </div>
@endsection