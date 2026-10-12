@extends('layouts.admin')

@section('title', 'Kecamatan')

@section('content')
        <div class="container-fluid px-3 px-lg-4 py-4">
          <div class="page-heading">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-geo-alt" aria-hidden="true"></i></span>
              <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">{{ $title }}</h1>
                <p class="text-muted mb-0">Kelola data kecamatan dan wilayah administratif.</p>
              </div>
            </div>
            <div class="heading-actions"> 
                <a href="{{ route('kecamatans.create') }}" class="btn btn-primary btn-sm py-2 px-3" > 
                    <i class="bi bi-plus-circle" aria-hidden="true"></i>
                    Tambah Kecamatan 
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
                <h2 class="h5 mb-1 section-title"><i class="bi bi-geo-alt" aria-hidden="true"></i><span>Daftar Kecamatan</span></h2>
                <p class="text-muted mb-0">List semua kecamatan yang terdaftar dalam sistem.</p>
              </div>
              <!-- Form Pencarian -->
              <form action="{{ route('kecamatans.index') }}" method="GET" class="w-100" style="max-width: 300px;">
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
                        aria-label="Cari kecamatan"
                    >
                </div>
              </form>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Kecamatan</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Dibuat</th>
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody> 
                    @forelse($kecamatans as $index => $kecamatan)
                    <tr>
                        <td>
                            {{ $kecamatans->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2"> 
                                <div class="avatar-img avatar-sm d-flex align-items-center justify-content-center bg-light"> 
                                    <i class="bi bi-building text-secondary"></i> 
                                </div> 
                                <div> 
                                    <p class="fw-semibold mb-0">{{ $kecamatan->nama }}</p> 
                                </div> 
                            </div> 
                        </td>
                        <td>
                            {{ $kecamatan->keterangan ?? '-' }}
                        </td>
                        <td> 
                            {{ $kecamatan->created_at->format('d M Y') }} 
                        </td>
                        <td class="text-center"> 
                            <div class="d-flex justify-content-center gap-1">
                                <a class="btn btn-light btn-sm text-info" href="{{ route('kecamatans.show', $kecamatan->id) }}" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a class="btn btn-light btn-sm text-primary" href="{{ route('kecamatans.edit', $kecamatan->id) }}" title="Edit"> 
                                    <i class="bi bi-pencil-square"></i> 
                                </a>  
                                <form action="{{ route('kecamatans.destroy', $kecamatan->id) }}" 
                                    method="POST" 
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE') 
                                    <button type="button" 
                                            class="btn btn-light btn-sm text-danger btn-delete" 
                                            title="Hapus"
                                            data-nama="{{ $kecamatan->nama }}"
                                            data-title="Hapus Kecamatan">
                                        <i class="bi bi-trash"></i> 
                                    </button> 
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-geo-alt fs-3 d-block mb-2"></i>
                                @if(request('search'))
                                    Tidak ada kecamatan yang sesuai dengan pencarian "{{ request('search') }}".
                                @else
                                    Belum ada kecamatan.
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
              </table>
            </div>
            <div class="mb-0 mt-3">
                {{ $kecamatans->links('pagination::bootstrap-5') }}
            </div>
          </section>
        </div>
@endsection