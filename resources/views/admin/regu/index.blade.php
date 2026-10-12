@extends('layouts.admin')

@section('title', 'Regu')

@section('content')
        <div class="container-fluid px-3 px-lg-4 py-4">
          <div class="page-heading">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
              <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">{{ $title ?? 'Regu' }}</h1>
                <p class="text-muted mb-0">Kelola data regu dan unit kerja.</p>
              </div>
            </div>
            <div class="heading-actions"> 
                <a href="{{ route('regus.create') }}" class="btn btn-primary btn-sm py-2 px-3" > 
                    <i class="bi bi-plus-circle" aria-hidden="true"></i>
                    Tambah Regu 
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

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> {{ session('error') }}

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
                <h2 class="h5 mb-1 section-title"><i class="bi bi-people" aria-hidden="true"></i><span>Daftar Regu</span></h2>
                <p class="text-muted mb-0">List semua regu yang terdaftar dalam sistem.</p>
              </div>
              <div class="d-flex gap-2 w-100" style="max-width: 500px;">
                <!-- Form Pencarian -->
                <form action="{{ route('regus.index') }}" method="GET" class="w-100" style="max-width: 300px;">
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
                          aria-label="Cari regu"
                      >
                  </div>
                </form>

                <!-- Filter Aktif -->
                <form action="{{ route('regus.index') }}" method="GET" class="w-100" style="max-width: 200px;">
                  @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                  @endif
                  <select
                      name="aktif"
                      class="form-select form-select-sm"
                      onchange="this.form.submit()"
                      aria-label="Filter status aktif"
                  >
                      <option value="">Semua Status</option>
                      <option value="1" {{ request('aktif') == '1' ? 'selected' : '' }}>Aktif</option>
                      <option value="0" {{ request('aktif') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                  </select>
                </form>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Regu</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Status</th>
                    <th scope="col">Dibuat</th>
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody> 
                    @forelse($regus as $index => $regu)
                    <tr>
                        <td>
                            {{ $regus->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2"> 
                                <div class="avatar-img avatar-sm d-flex align-items-center justify-content-center bg-light"> 
                                    <i class="bi bi-people text-secondary"></i> 
                                </div> 
                                <div> 
                                    <p class="fw-semibold mb-0">{{ $regu->nama }}</p> 
                                </div> 
                            </div> 
                        </td>
                        <td>
                            {{ $regu->keterangan ?? '-' }}
                        </td>
                        
                        <td>
                            @if($regu->aktif)
                                <span class="badge" style="background-color: #bbf7d0; color: #15803d;">
                                     Aktif
                                </span>
                            @else
                                <span class="badge" style="background-color: #fee2e2; color: #b91c1c;">
                                     Tidak Aktif
                                </span>
                            @endif
                        </td>

                        <td> 
                            {{ $regu->created_at->format('d M Y') }} 
                        </td>
                        <td class="text-center"> 
                            <div class="d-flex justify-content-center gap-1">
                                <a class="btn btn-light btn-sm text-info" href="{{ route('regus.show', $regu->id) }}" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a class="btn btn-light btn-sm text-primary" href="{{ route('regus.edit', $regu->id) }}" title="Edit"> 
                                    <i class="bi bi-pencil-square"></i> 
                                </a>  
                                <form action="{{ route('regus.destroy', $regu->id) }}" 
                                    method="POST" 
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE') 
                                    <button type="button" 
                                            class="btn btn-light btn-sm text-danger btn-delete" 
                                            title="Hapus"
                                            data-nama="{{ $regu->nama }}"
                                            data-title="Hapus Regu">
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
                                <i class="bi bi-people fs-3 d-block mb-2"></i>
                                @if(request('search') || request('aktif'))
                                    Tidak ada regu yang sesuai dengan pencarian atau filter yang dipilih.
                                @else
                                    Belum ada regus.
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
              </table>
            </div>
            <div class="mb-0 mt-3">
                {{ $regus->links('pagination::bootstrap-5') }}
            </div>
          </section>
        </div>
@endsection