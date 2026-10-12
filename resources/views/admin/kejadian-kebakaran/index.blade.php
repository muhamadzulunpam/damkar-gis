@extends('layouts.admin')

@section('title', 'Kejadian Kebakaran')

@section('content')
        <div class="container-fluid px-3 px-lg-4 py-4">
          <div class="page-heading">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-fire" aria-hidden="true"></i></span>
              <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">{{ $title ?? 'Kejadian Kebakaran' }}</h1>
                <p class="text-muted mb-0">Kelola data kejadian kebakaran.</p>
              </div>
            </div>
            <div class="heading-actions"> 
                <a href="{{ route('kejadian-kebakarans.create') }}" class="btn btn-primary btn-sm py-2 px-3" > 
                    <i class="bi bi-plus-circle" aria-hidden="true"></i>
                    Tambah Kejadian 
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
                <h2 class="h5 mb-1 section-title"><i class="bi bi-fire" aria-hidden="true"></i><span>Daftar Kejadian Kebakaran</span></h2>
                <p class="text-muted mb-0">List semua kejadian kebakaran yang terdaftar dalam sistem.</p>
              </div>
              <div class="d-flex gap-2 w-100" style="max-width: 750px;">
                <!-- Form Pencarian -->
                <form action="{{ route('kejadian-kebakarans.index') }}" method="GET" class="w-100" style="max-width: 250px;">
                  <div class="position-relative">
                      <i
                          class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"
                          aria-hidden="true"
                      ></i>

                      <input
                          type="search"
                          name="search"
                          class="form-control form-control-sm ps-5"
                          placeholder="Cari ID, alamat, pemilik..."
                          value="{{ request('search') }}"
                          aria-label="Cari kejadian kebakaran"
                      >
                  </div>
                </form>

                <!-- Filter Kecamatan -->
                <form action="{{ route('kejadian-kebakarans.index') }}" method="GET" class="w-100" style="max-width: 180px;">
                  @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                  @endif
                  @if(request('kategori_jenis'))
                    <input type="hidden" name="kategori_jenis" value="{{ request('kategori_jenis') }}">
                  @endif
                  @if(request('tanggal_mulai'))
                    <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                  @endif
                  @if(request('tanggal_selesai'))
                    <input type="hidden" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">
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

                <!-- Filter Kategori Jenis -->
                <form action="{{ route('kejadian-kebakarans.index') }}" method="GET" class="w-100" style="max-width: 180px;">
                  @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                  @endif
                  @if(request('kecamatan_id'))
                    <input type="hidden" name="kecamatan_id" value="{{ request('kecamatan_id') }}">
                  @endif
                  @if(request('tanggal_mulai'))
                    <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                  @endif
                  @if(request('tanggal_selesai'))
                    <input type="hidden" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">
                  @endif
                  <select
                      name="kategori_jenis"
                      class="form-select form-select-sm"
                      onchange="this.form.submit()"
                      aria-label="Filter kategori jenis"
                  >
                      <option value="">Semua Kategori</option>
                      @foreach($kategoriJenis as $kategori)
                          <option value="{{ $kategori }}" {{ request('kategori_jenis') == $kategori ? 'selected' : '' }}>
                              {{ $kategori }}
                          </option>
                      @endforeach
                  </select>
                </form>
              </div>
            </div>

            <!-- Filter Tanggal -->
            <div class="panel-header border-top pt-3 mt-0">
              <div class="d-flex gap-2 align-items-center">
                <form action="{{ route('kejadian-kebakarans.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                  @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                  @endif
                  @if(request('kecamatan_id'))
                    <input type="hidden" name="kecamatan_id" value="{{ request('kecamatan_id') }}">
                  @endif
                  @if(request('kategori_jenis'))
                    <input type="hidden" name="kategori_jenis" value="{{ request('kategori_jenis') }}">
                  @endif
                  <div class="d-flex align-items-center gap-2">
                    <label class="text-muted small mb-0">Tanggal:</label>
                    <input
                        type="date"
                        name="tanggal_mulai"
                        class="form-control form-control-sm"
                        style="width: 150px;"
                        value="{{ request('tanggal_mulai') }}"
                        aria-label="Tanggal mulai"
                    >
                    <span class="text-muted small">s/d</span>
                    <input
                        type="date"
                        name="tanggal_selesai"
                        class="form-control form-control-sm"
                        style="width: 150px;"
                        value="{{ request('tanggal_selesai') }}"
                        aria-label="Tanggal selesai"
                    >
                    <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                    @if(request('tanggal_mulai') || request('tanggal_selesai'))
                        <a href="{{ route('kejadian-kebakarans.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    @endif
                  </div>
                </form>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th scope="col">ID Kejadian</th>
                    <th scope="col">Alamat / Lokasi</th>
                    <th scope="col">Jenis & Kategori</th>
                    <th scope="col">Wilayah</th>
                    <th scope="col">Tanggal & Waktu</th>
                    <th scope="col">Kelompok & Korban</th>
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody> 
                    @forelse($kejadianKebakarans as $index => $kejadian)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2"> 
                                <div class="avatar-img avatar-sm d-flex align-items-center justify-content-center bg-light"> 
                                    <i class="bi bi-hash text-secondary"></i> 
                                </div> 
                                <div> 
                                    <p class="fw-semibold mb-0">{{ $kejadian->id_kejadian }}</p>
                                </div> 
                            </div> 
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2"> 
                                <div class="avatar-img avatar-sm d-flex align-items-center justify-content-center bg-light"> 
                                    <i class="bi bi-geo-alt text-secondary"></i> 
                                </div> 
                                <div> 
                                    <p class="fw-semibold mb-0">{{ Str::limit($kejadian->alamat_lengkap, 40) }}</p>
                                    @if($kejadian->nama_pemilik)
                                        <small class="text-muted">Pemilik: {{ Str::limit($kejadian->nama_pemilik, 25) }}</small>
                                    @endif
                                </div> 
                            </div> 
                        </td>
                        <td>
                            <div>
                                <span class="fw-semibold">{{ $kejadian->jenis_kejadian ?? '-' }}</span>
                                <br>
                                <small class="text-muted">{{ $kejadian->kategori_jenis ?? '-' }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span>{{ $kejadian->kecamatan->nama ?? '-' }}</span>
                                <br>
                                <small class="text-muted">{{ $kejadian->kelurahan->nama ?? '-' }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                {{ $kejadian->tanggal ? \Carbon\Carbon::parse($kejadian->tanggal)->format('d M Y') : '-' }}
                                <br>
                                <small class="text-muted">{{ $kejadian->waktu_kejadian ?? '-' }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column align-items-start gap-1">
                                @if($kejadian->kelompok_kebakaran)
                                <span class="badge" style="background-color: #fee2e2; color: #b91c1c;">
                                    {{ $kejadian->kelompok_kebakaran }}
                                </span>
                                @endif
                                @if($kejadian->korban_jiwa)
                                <span class="badge" style="background-color: #fef3c7; color: #b45309;">
                                    Korban: {{ $kejadian->korban_jiwa }} jiwa
                                </span>
                                @endif
                                <small class="text-muted">Unit: {{ $kejadian->jumlah_unit ?? 0 }}</small>
                            </div>
                        </td>
                        <td class="text-center"> 
                            <div class="d-flex justify-content-center gap-1">
                                <a class="btn btn-light btn-sm text-info" href="{{ route('kejadian-kebakarans.show', $kejadian->id) }}" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a class="btn btn-light btn-sm text-primary" href="{{ route('kejadian-kebakarans.edit', $kejadian->id) }}" title="Edit"> 
                                    <i class="bi bi-pencil-square"></i> 
                                </a>  
                                <form action="{{ route('kejadian-kebakarans.destroy', $kejadian->id) }}" 
                                    method="POST" 
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE') 
                                    <button type="button" 
                                            class="btn btn-light btn-sm text-danger btn-delete" 
                                            title="Hapus"
                                            data-nama="{{ $kejadian->kelurahan->nama ?? 'Kejadian Kebakaran' }}"
                                            data-title="Hapus Kejadian Kebakaran">
                                        <i class="bi bi-trash"></i> 
                                    </button> 
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-fire fs-3 d-block mb-2"></i>
                                @if(request('search') || request('kecamatan_id') || request('kategori_jenis') || request('tanggal_mulai') || request('tanggal_selesai'))
                                    Tidak ada kejadian kebakaran yang sesuai dengan pencarian atau filter yang dipilih.
                                @else
                                    Belum ada kejadian kebakaran.
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
              </table>
            </div>
            <div class="mb-0 mt-3">
                {{ $kejadianKebakarans->links('pagination::bootstrap-5') }}
            </div>
          </section>
        </div>
@endsection