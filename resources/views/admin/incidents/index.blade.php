@extends('layouts.admin')

@section('title', 'Incident')

@section('content')
        <div class="container-fluid px-3 px-lg-4 py-4">
          <div class="page-heading">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></span>
              <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">{{ $title ?? 'Incident' }}</h1>
                <p class="text-muted mb-0">Kelola data incident dan kejadian.</p>
              </div>
            </div>
            <div class="heading-actions"> 
                <a href="{{ route('incidents.create') }}" class="btn btn-primary btn-sm py-2 px-3" > 
                    <i class="bi bi-plus-circle" aria-hidden="true"></i>
                    Tambah Incident 
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
                <h2 class="h5 mb-1 section-title"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i><span>Daftar Incident</span></h2>
                <p class="text-muted mb-0">List semua incident yang terdaftar dalam sistem.</p>
              </div>
              <div class="d-flex gap-2 w-100" style="max-width: 750px;">
                <!-- Form Pencarian -->
                <form action="{{ route('incidents.index') }}" method="GET" class="w-100" style="max-width: 250px;">
                  <div class="position-relative">
                      <i
                          class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"
                          aria-hidden="true"
                      ></i>

                      <input
                          type="search"
                          name="search"
                          class="form-control form-control-sm ps-5"
                          placeholder="Cari alamat atau deskripsi..."
                          value="{{ request('search') }}"
                          aria-label="Cari incident"
                      >
                  </div>
                </form>

                <!-- Filter Kecamatan -->
                <form action="{{ route('incidents.index') }}" method="GET" class="w-100" style="max-width: 180px;">
                  @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                  @endif
                  @if(request('jenis_kejadian_id'))
                    <input type="hidden" name="jenis_kejadian_id" value="{{ request('jenis_kejadian_id') }}">
                  @endif
                  @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
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

                <!-- Filter Jenis Kejadian -->
                <form action="{{ route('incidents.index') }}" method="GET" class="w-100" style="max-width: 180px;">
                  @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                  @endif
                  @if(request('kecamatan_id'))
                    <input type="hidden" name="kecamatan_id" value="{{ request('kecamatan_id') }}">
                  @endif
                  @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                  @endif
                  @if(request('tanggal_mulai'))
                    <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                  @endif
                  @if(request('tanggal_selesai'))
                    <input type="hidden" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">
                  @endif
                  <select
                      name="jenis_kejadian_id"
                      class="form-select form-select-sm"
                      onchange="this.form.submit()"
                      aria-label="Filter jenis kejadian"
                  >
                      <option value="">Semua Jenis</option>
                      @foreach($jenisKejadians as $jenis)
                          <option value="{{ $jenis->id }}" {{ request('jenis_kejadian_id') == $jenis->id ? 'selected' : '' }}>
                              {{ $jenis->nama }}
                          </option>
                      @endforeach
                  </select>
                </form>

                <!-- Filter Status -->
                <form action="{{ route('incidents.index') }}" method="GET" class="w-100" style="max-width: 140px;">
                  @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                  @endif
                  @if(request('kecamatan_id'))
                    <input type="hidden" name="kecamatan_id" value="{{ request('kecamatan_id') }}">
                  @endif
                  @if(request('jenis_kejadian_id'))
                    <input type="hidden" name="jenis_kejadian_id" value="{{ request('jenis_kejadian_id') }}">
                  @endif
                  @if(request('tanggal_mulai'))
                    <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                  @endif
                  @if(request('tanggal_selesai'))
                    <input type="hidden" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">
                  @endif
                  <select
                      name="status"
                      class="form-select form-select-sm"
                      onchange="this.form.submit()"
                      aria-label="Filter status"
                  >
                      <option value="">Semua Status</option>
                      <option value="dilaporkan" {{ request('status') == 'dilaporkan' ? 'selected' : '' }}>Dilaporkan</option>
                      <option value="ditangani" {{ request('status') == 'ditangani' ? 'selected' : '' }}>Ditangani</option>
                      <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                  </select>
                </form>
              </div>
            </div>

            <!-- Filter Tanggal -->
            <div class="panel-header border-top pt-3 mt-0">
              <div class="d-flex gap-2 align-items-center">
                <form action="{{ route('incidents.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                  @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                  @endif
                  @if(request('kecamatan_id'))
                    <input type="hidden" name="kecamatan_id" value="{{ request('kecamatan_id') }}">
                  @endif
                  @if(request('jenis_kejadian_id'))
                    <input type="hidden" name="jenis_kejadian_id" value="{{ request('jenis_kejadian_id') }}">
                  @endif
                  @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
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
                        <a href="{{ route('incidents.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    @endif
                  </div>
                </form>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Alamat / Lokasi</th>
                    <th scope="col">Jenis & Kategori</th>
                    <th scope="col">Wilayah</th>
                    <th scope="col">Tanggal & Waktu</th>
                    <th scope="col">Korban</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody> 
                    @forelse($incidents as $index => $incident)
                    <tr>
                        <td>
                            {{ $incidents->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2"> 
                                <div class="avatar-img avatar-sm d-flex align-items-center justify-content-center bg-light"> 
                                    <i class="bi bi-geo-alt text-secondary"></i> 
                                </div> 
                                <div> 
                                    <p class="fw-semibold mb-0">{{ Str::limit($incident->alamat, 40) }}</p>
                                    @if($incident->deskripsi)
                                        <small class="text-muted">{{ Str::limit($incident->deskripsi, 30) }}</small>
                                    @endif
                                </div> 
                            </div> 
                        </td>
                        <td>
                            <div>
                                <span class="fw-semibold">{{ $incident->jenisKejadian->nama ?? '-' }}</span>
                                <br>
                                <small class="text-muted">{{ $incident->kategoriKejadian->nama ?? '-' }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span>{{ $incident->kecamatan->nama ?? '-' }}</span>
                                <br>
                                <small class="text-muted">{{ $incident->kelurahan->nama ?? '-' }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                {{ $incident->tanggal_kejadian ? \Carbon\Carbon::parse($incident->tanggal_kejadian)->format('d M Y') : '-' }}
                                <br>
                                <small class="text-muted">{{ $incident->waktu_kejadian ?? '-' }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column align-items-start gap-1">
                                <span class="badge" style="background-color: #fee2e2; color: #b91c1c;">
                                    Meninggal: {{ $incident->jumlah_meninggal }}
                                </span>
                                <span class="badge" style="background-color: #fef3c7; color: #b45309;">
                                    Luka: {{ $incident->jumlah_luka }}
                                </span>
                                <br>
                                <small class="text-muted">Total: {{ $incident->jumlah_korban }}</small>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'dilaporkan' => ['bg' => '#fef3c7', 'color' => '#b45309'],
                                    'ditangani' => ['bg' => '#bfdbfe', 'color' => '#1e40af'],
                                    'selesai' => ['bg' => '#bbf7d0', 'color' => '#15803d'],
                                ];
                                $statusLabels = [
                                    'dilaporkan' => 'Dilaporkan',
                                    'ditangani' => 'Ditangani',
                                    'selesai' => 'Selesai',
                                ];
                                $colors = $statusColors[$incident->status] ?? ['bg' => '#e5e7eb', 'color' => '#374151'];
                            @endphp
                            <span class="badge" style="background-color: {{ $colors['bg'] }}; color: {{ $colors['color'] }};">
                                {{ $statusLabels[$incident->status] ?? $incident->status }}
                            </span>
                        </td>
                        <td class="text-center"> 
                            <div class="d-flex justify-content-center gap-1">
                                <a class="btn btn-light btn-sm text-info" href="{{ route('incidents.show', $incident->id) }}" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a class="btn btn-light btn-sm text-primary" href="{{ route('incidents.edit', $incident->id) }}" title="Edit"> 
                                    <i class="bi bi-pencil-square"></i> 
                                </a>  
                                <form action="{{ route('incidents.destroy', $incident->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus incident ini?')">
                                    @csrf
                                    @method('DELETE') 
                                    <button type="submit" class="btn btn-light btn-sm text-danger" title="Hapus">
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
                                <i class="bi bi-exclamation-triangle fs-3 d-block mb-2"></i>
                                @if(request('search') || request('kecamatan_id') || request('jenis_kejadian_id') || request('status') || request('tanggal_mulai') || request('tanggal_selesai'))
                                    Tidak ada incident yang sesuai dengan pencarian atau filter yang dipilih.
                                @else
                                    Belum ada incident.
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
              </table>
            </div>
            <div class="mb-0 mt-3">
                {{ $incidents->links('pagination::bootstrap-5') }}
            </div>
          </section>
        </div>
@endsection 