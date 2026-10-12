@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
        <div class="container-fluid px-3 px-lg-4 py-4">
          <div class="page-heading">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
              <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">{{ $title }}</h1>
                <p class="text-muted mb-0">Kelola akun pengguna dan hak akses sistem.</p>
              </div>
            </div>
            <div class="heading-actions"> 
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm py-2 px-3" > 
                    <i class="bi bi-person-plus" aria-hidden="true"></i>
                    Tambah User 
                </a> 
            </div>
          </div>

          <section class="row g-3 mt-1" aria-label="Dashboard metrics">
            <div class="col-12 col-sm-6 col-xl-4">
              <article class="metric-card metric-primary">
                <div class="metric-top">
                  <span class="metric-label">admin</span>
                  <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $totalAdmin }}</div>
              </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-4">
              <article class="metric-card metric-success">
                <div class="metric-top">
                  <span class="metric-label">petugas</span>
                  <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $totalPetugas }}</div>
              </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-4">
              <article class="metric-card metric-warning">
                <div class="metric-top">
                  <span class="metric-label">pimpinan</span>
                  <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $totalPimpinan }}</div>
              </article>
            </div>
          </section>
        <!-- Recent Users -->
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
                <h2 class="h5 mb-1 section-title"><i class="bi bi-people" aria-hidden="true"></i><span>Daftar Pengguna</span></h2>
                <p class="text-muted mb-0">List semua akun yang terdaftar dalam aplikasi.</p>
              </div>
              <!-- Form Pencarian -->
              <form action="{{ route('users.index') }}" method="GET" class="w-100" style="max-width: 300px;">
                <div class="position-relative">
                    <i
                        class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"
                        aria-hidden="true"
                    ></i>

                    <input
                        type="search"
                        name="search"
                        class="form-control form-control-sm ps-5"
                        placeholder="Cari nama atau email..."
                        value="{{ request('search') }}"
                        aria-label="Cari pengguna"
                    >
                </div>
              </form>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0"> <thead> <tr> <th scope="col">User</th> <th scope="col">Role</th> <th scope="col">Dibuat</th> <th scope="col" class="text-center">Action</th> </tr> </thead>
                <tbody> 
                    @forelse($users as $user)
                    <tr> {{-- User --}} <td> 
                        <div class="d-flex align-items-center gap-2"> 
                            <div class="avatar-img avatar-sm d-flex align-items-center justify-content-center bg-light" > 
                                <i class="bi bi-person text-secondary"></i> 
                            </div> 
                            <div> 
                                <p class="fw-semibold mb-0"> {{ $user->name }} </p> 
                                <p class="text-muted small mb-0"> {{ $user->email }} </p> 
                            </div> 
                        </div> 
                    </td>  
                    <td> 
                        @if($user->role === 'admin') 
                        <span class="badge text-bg-primary"> 
                            Admin 
                        </span>
                        @elseif($user->role === 'petugas') 
                        <span class="badge text-bg-success">
                             Petugas 
                        </span>
                        @elseif($user->role === 'pimpinan') 
                        <span class="badge text-bg-warning"> 
                            Pimpinan 
                        </span> 
                        @endif 
                    </td>
                    <td> 
                        {{ $user->created_at->format('d M Y') }} 
                    </td>
                    <td class="text-center"> 
                        <div class="d-flex justify-content-center gap-1"> 
                            <a class="btn btn-light btn-sm text-info" href="{{ route('users.show', $user->id) }}" title="Detail">
                                    <i class="bi bi-eye"></i>
                            </a>
                            <a class="btn btn-light btn-sm text-primary" href="{{ route('users.edit', $user->id) }}" title="Edit" > 
                                <i class="bi bi-pencil-square"></i> 
                            </a>  
                            <form action="{{ route('users.destroy', $user->id) }}" 
                                    method="POST" 
                                    class="d-inline delete-form">
                                  @csrf
                                  @method('DELETE') 
                                  <button type="button" 
                                          class="btn btn-light btn-sm text-danger btn-delete" 
                                          title="Hapus"
                                          data-nama="{{ $user->name }}"
                                          data-title="Hapus Pengguna">
                                      <i class="bi bi-trash"></i> 
                                  </button> 
                              </form>
                      </div> 
                    </td> 
                  </tr> @empty <tr> 
                    <td colspan="4" class="text-center py-4"> 
                      <div class="text-muted"> 
                        <i class="bi bi-people fs-3 d-block mb-2"></i> Belum ada pengguna. 
                      </div> 
                    </td> 
                  </tr> 
                  @endforelse 
                </tbody>
              </table>
            </div>
            <div class="mb-0 mt-3">
              {{ $users->links('pagination::bootstrap-5') }}
            </div>
          </section>
        </div>
@endsection      