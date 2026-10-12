<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="adminHMD professional admin dashboard template">
  <link rel="icon" type="image/png" href="{{ asset('img/logo_damkar.png') }}">
  <title>{{ $title }} | admin Damkar GIS</title>

  <link rel="stylesheet" href="{{ asset('css/admin/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin/style.css') }}">
  <style>
    /* Container submenu */
    .collapse .nav-link.nav-sub {
        position: relative;
        padding-left: 2.8rem;
    }

    .collapse .nav-link.nav-sub::before {
        content: "";
        position: absolute;
        left: 1.6rem;
        top: 0;
        bottom: 0;
        width: 1px;
        background: rgba(255, 255, 255, 0.15) !important;
    }
    .logo-admin {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
    }

    /* ============================
      SIDEBAR COLLAPSE / TOGGLE
      ============================ */
    .admin-sidebar {
        transition: transform .3s ease, width .3s ease, margin-left .3s ease;
        width: 260px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 1040;
        overflow-y: auto;
    }

    .admin-main {
        margin-left: 260px;
        transition: margin-left .3s ease;
        min-height: 100vh;
    }

    @media (min-width: 992px) {
        body.sidebar-collapsed .admin-sidebar {
            margin-left: -260px;
        }
        body.sidebar-collapsed .admin-main {
            margin-left: 0;
        }
    }

    @media (max-width: 991.98px) {
        .admin-sidebar {
            transform: translateX(-100%);
        }
        .admin-main {
            margin-left: 0;
        }
        body.sidebar-open .admin-sidebar {
            transform: translateX(0);
        }
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 1035;
            opacity: 0;
            transition: opacity .3s ease;
        }
        body.sidebar-open .sidebar-backdrop {
            display: block;
            opacity: 1;
        }
    }

    .sidebar-toggle {
        display: inline-flex;
        flex-direction: column;
        justify-content: center;
        gap: 4px;
        width: 38px;
        height: 38px;
        padding: 0 8px;
        background: transparent;
        border: 0;
        cursor: pointer;
    }

    .sidebar-toggle span {
        display: block;
        height: 2px;
        background: currentColor;
        border-radius: 2px;
        transition: transform .3s ease, opacity .3s ease;
    }

    .sidebar-toggle span:nth-child(2) {
        width: 70%;
    }

    body.sidebar-collapsed .sidebar-toggle span:nth-child(1) {
        transform: translateY(6px) rotate(45deg);
    }
    body.sidebar-collapsed .sidebar-toggle span:nth-child(2) {
        opacity: 0;
    }
    body.sidebar-collapsed .sidebar-toggle span:nth-child(3) {
        transform: translateY(-6px) rotate(-45deg);
    }
  </style>
   @stack('styles')
</head>

<body>
  @php
      $role = auth()->user()->role ?? null;
      $isAdmin   = $role === 'admin';
      $isPetugas = $role === 'petugas';
      $isBoth    = $isAdmin || $isPetugas;
  @endphp

  <div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
      <div class="sidebar-header">
        <a class="brand-mark" aria-label="adminHMD dashboard">
          <img class="logo-admin" src="{{ asset('img/logo_damkar.png') }}" alt="Logo Damkar GIS">
          <span class="brand-copy">
            <span class="brand-title">{{ auth()->user()->name }}</span>
            <span class="brand-subtitle text-capitalize">{{ $role }}</span>
          </span>
        </a>
      </div>

      <nav class="sidebar-nav">

        {{-- Dashboard (admin, petugas) --}}
        @if($isBoth)
          <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            href="{{ route('dashboard') }}">
              <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
              <span class="nav-text">Dashboard</span>
          </a>
        @endif


        {{-- Data Master (admin) --}}
        @if($isAdmin)
          <div class="nav-group">
              <a class="nav-link nav-toggle" data-bs-toggle="collapse" href="#menuWilayah" role="button"
                aria-expanded="{{ request()->routeIs('kecamatans.*') || request()->routeIs('kelurahans.*') || request()->routeIs('penduduk.*') || request()->routeIs('regus.*') ? 'true' : 'false' }}"
                aria-controls="menuWilayah">
                  <span class="nav-icon"><i class="bi bi-geo-alt" aria-hidden="true"></i></span>
                  <span class="nav-text">Data Master</span>
                  <i class="bi bi-chevron-down ms-auto nav-arrow" aria-hidden="true"></i>
              </a>
              <div class="collapse {{ request()->routeIs('kecamatans.*') || request()->routeIs('kelurahans.*') || request()->routeIs('penduduk.*') || request()->routeIs('regus.*') ? 'show' : '' }}" id="menuWilayah">

                  <a class="nav-link nav-sub {{ request()->routeIs('kecamatans.index') ? 'active' : '' }}" href="{{ route('kecamatans.index') }}">
                    <span class="nav-icon"><i class="bi bi-building-fill-gear" aria-hidden="true"></i></span>
                    <span class="nav-text">Kecamatan</span>
                  </a>

                  <a class="nav-link nav-sub {{ request()->routeIs('kelurahans.index') ? 'active' : '' }}" href="{{ route('kelurahans.index') }}">
                    <span class="nav-icon"><i class="bi bi-geo-alt" aria-hidden="true"></i></span>
                    <span class="nav-text">Kelurahan</span>
                  </a>

                  <a class="nav-link nav-sub {{ request()->routeIs('penduduk.index') ? 'active' : '' }}" href="{{ route('penduduk.index') }}">
                    <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                    <span class="nav-text">Penduduk</span>
                  </a>

                  <a class="nav-link nav-sub {{ request()->routeIs('regus.index') ? 'active' : '' }}" href="{{ route('regus.index') }}">
                    <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                    <span class="nav-text">Regu</span>
                  </a>

              </div>
          </div>
        @endif


        {{-- Data Kejadian (admin, petugas) --}}
        @if($isBoth)
          <div class="nav-group">
              <a class="nav-link nav-toggle" data-bs-toggle="collapse" href="#menuKejadian" role="button"
                aria-expanded="{{ request()->routeIs('kejadian-kebakarans.*') || request()->routeIs('kejadian-penyelamatans.*') ? 'true' : 'false' }}"
                aria-controls="menuKejadian">
                  <span class="nav-icon"><i class="bi bi-clipboard-data" aria-hidden="true"></i></span>
                  <span class="nav-text">Data Kejadian</span>
                  <i class="bi bi-chevron-down ms-auto nav-arrow" aria-hidden="true"></i>
              </a>
              <div class="collapse {{ request()->routeIs('kejadian-kebakarans.*') || request()->routeIs('kejadian-penyelamatans.*') ? 'show' : '' }}" id="menuKejadian">

                  <a class="nav-link nav-sub {{ request()->routeIs('kejadian-kebakarans.index') ? 'active' : '' }}" href="{{ route('kejadian-kebakarans.index') }}">
                    <span class="nav-icon"><i class="bi bi-fire" aria-hidden="true"></i></span>
                    <span class="nav-text">Kebakaran</span>
                  </a>

                  <a class="nav-link nav-sub {{ request()->routeIs('kejadian-penyelamatans.index') ? 'active' : '' }}" href="{{ route('kejadian-penyelamatans.index') }}">
                    <span class="nav-icon"><i class="bi bi-person-fill" aria-hidden="true"></i></span>
                    <span class="nav-text">Penyelamatan</span>
                  </a>

              </div>
          </div>
        @endif


        {{-- Peta Kerawanan (admin, petugas) --}}
        @if($isBoth)
          <div class="nav-group">
              <a class="nav-link nav-toggle" data-bs-toggle="collapse" href="#menuPeta" role="button"
                aria-expanded="{{ request()->routeIs('peta-kerawanan.*') || request()->routeIs('analisis-kmeans.*') ? 'true' : 'false' }}"
                aria-controls="menuPeta">
                  <span class="nav-icon"><i class="bi bi-map" aria-hidden="true"></i></span>
                  <span class="nav-text">Peta Kerawanan</span>
                  <i class="bi bi-chevron-down ms-auto nav-arrow" aria-hidden="true"></i>
              </a>
              <div class="collapse {{ request()->routeIs('peta-kerawanan.*') || request()->routeIs('analisis-kmeans.*') ? 'show' : '' }}" id="menuPeta">

                  <a class="nav-link nav-sub {{ request()->routeIs('peta-kerawanan.index') ? 'active' : '' }}" href="{{ route('peta-kerawanan.index') }}">
                    <span class="nav-icon"><i class="bi bi-map" aria-hidden="true"></i></span>
                    <span class="nav-text">Peta Kerawanan</span>
                  </a>

                  {{-- Analisis K-Means hanya admin --}}
                  @if($isAdmin)
                    <a class="nav-link nav-sub {{ request()->routeIs('analisis-kmeans.*') ? 'active' : '' }}" href="{{ route('analisis-kmeans.index') }}">
                      <span class="nav-icon"><i class="bi bi-list-ul" aria-hidden="true"></i></span>
                      <span class="nav-text">Analisis K-Means</span>
                    </a>
                  @endif

              </div>
          </div>
        @endif


        {{-- Laporan (admin) --}}
        @if($isAdmin)
          <div class="nav-group">
              <a class="nav-link nav-toggle" data-bs-toggle="collapse" href="#menuLaporan" role="button"
                aria-expanded="{{ request()->routeIs('laporan.*') ? 'true' : 'false' }}"
                aria-controls="menuLaporan">
                  <span class="nav-icon"><i class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                  <span class="nav-text">Laporan</span>
                  <i class="bi bi-chevron-down ms-auto nav-arrow" aria-hidden="true"></i>
              </a>
              <div class="collapse {{ request()->routeIs('laporan.*') ? 'show' : '' }}" id="menuLaporan">

                  <a class="nav-link nav-sub {{ request()->routeIs('laporan.index') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                    <span class="nav-icon"><i class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                    <span class="nav-text">Laporan</span>
                  </a>

              </div>
          </div>
        @endif


        {{-- Pengaturan (admin, petugas) --}}
        @if($isBoth)
          <div class="nav-group">
              <a class="nav-link nav-toggle" data-bs-toggle="collapse" href="#menuPengaturan" role="button"
                aria-expanded="{{ request()->routeIs('users.*') || request()->routeIs('activity-log.*') || request()->routeIs('profile.*') ? 'true' : 'false' }}"
                aria-controls="menuPengaturan">
                  <span class="nav-icon"><i class="bi bi-gear" aria-hidden="true"></i></span>
                  <span class="nav-text">Pengaturan</span>
                  <i class="bi bi-chevron-down ms-auto nav-arrow" aria-hidden="true"></i>
              </a>
              <div class="collapse {{ request()->routeIs('users.*') || request()->routeIs('activity-log.*') || request()->routeIs('profile.*') ? 'show' : '' }}" id="menuPengaturan">

                  {{-- Management User hanya admin --}}
                  @if($isAdmin)
                    <a class="nav-link nav-sub {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                      <span class="nav-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                      <span class="nav-text">Management User</span>
                    </a>
                    
                    <a class="nav-link nav-sub {{ request()->routeIs('activity-log.index') ? 'active' : '' }}" href="{{ route('activity-log.index') }}">
                        <span class="nav-icon"><i class="bi bi-clock" aria-hidden="true"></i></span>
                        <span class="nav-text">Activity Log</span>
                    </a>
                    @endif

                  <a class="nav-link nav-sub {{ request()->routeIs('profile.index') ? 'active' : '' }}" href="{{ route('profile.index') }}">
                    <span class="nav-icon"><i class="bi bi-person" aria-hidden="true"></i></span>
                    <span class="nav-text">Profile</span>
                  </a>

              </div>
          </div>
        @endif

      </nav>

      <div class="sidebar-footer">
        @if($isBoth)
          <a class="btn btn-primary w-100" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
          </a>
          <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        @endif
      </div>
    </aside>

    <div class="admin-main">
      <nav class="navbar admin-navbar navbar-expand bg-white">
        <div class="container-fluid px-3 px-lg-4">
          <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
            <span></span>
            <span></span>
            <span></span>
          </button>

          <div class="navbar-actions ms-auto">
            <button class="icon-button theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
              <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
            </button>

            @php
              $notifications = \App\Models\ActivityLog::with('user')
                  ->latest()
                  ->limit(5)
                  ->get();
            @endphp

            <div class="dropdown">
                <button class="icon-button" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                    @if($notifications->isNotEmpty())
                        <span class="notification-dot"></span>
                    @endif
                    <i class="bi bi-bell" aria-hidden="true"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-end notification-menu p-0 shadow-sm" style="width: 320px;">
                    <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary-emphasis px-3 py-2 rounded-top">
                        <span class="fw-bold">
                            <i class="bi bi-bell-fill me-2"></i>
                            Notifications
                        </span>
                        @if($notifications->isNotEmpty())
                            <span class="badge bg-primary rounded-pill">
                                {{ $notifications->count() }}
                            </span>
                        @endif
                    </div>

                    <div style="max-height: 320px; overflow-y: auto;">
                        @forelse($notifications as $notification)
                            <a class="dropdown-item px-3 py-2 border-bottom" href="{{ route('activity-log.index') }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span class="notification-title text-wrap">
                                        {{ Str::limit($notification->description, 40) }}
                                    </span>
                                </div>
                                <span class="notification-time text-muted small">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </a>
                        @empty
                            <div class="dropdown-item px-3 py-4 text-center text-muted">
                                <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                Belum ada aktivitas.
                            </div>
                        @endforelse
                    </div>

                    @if($notifications->isNotEmpty())
                        <a href="{{ route('activity-log.index') }}" class="dropdown-item text-center text-primary fw-semibold py-2 border-top">
                            Lihat Semua Aktivitas
                        </a>
                    @endif
                </div>
            </div>

            <div class="dropdown">
              <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img class="avatar-img avatar-sm" src="{{ asset('images/avatar/avatar.jpg') }}" alt="{{ auth()->user()->name }}">
                <span class="profile-name d-none d-sm-inline">{{ auth()->user()->name }}</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.index') }}">Profile</a></li>
                <li><a class="dropdown-item" href="{{ route('home') }}">Halaman Utama</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                      onclick="event.preventDefault(); document.getElementById('logout-form-topbar').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                    <form id="logout-form-topbar" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </nav>

      <main class="dashboard-content">
         @yield('content')
      </main>

      <footer class="admin-footer">
        <div class="container-fluid px-3 px-lg-4">
          <span>Copyright 2026 Dinas Damkar, <br>dan Penyelamatan Kota Tangerang Selatan</span>
          <span>Professional dashboard template.</span>
        </div>
      </footer>
    </div>
  </div>

  <script src="{{ asset('js/admin/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/admin/main.js') }}"></script>
  <script>
  (function () {
      const body      = document.body;
      const toggleBtn = document.querySelector('[data-sidebar-toggle]');
      const closeBtn  = document.querySelector('[data-sidebar-close]');

      const savedState = localStorage.getItem('sidebar-collapsed');
      if (savedState === 'true' && window.innerWidth >= 992) {
          body.classList.add('sidebar-collapsed');
      }

      if (toggleBtn) {
          toggleBtn.addEventListener('click', function (e) {
              e.preventDefault();
              if (window.innerWidth < 992) {
                  body.classList.toggle('sidebar-open');
              } else {
                  body.classList.toggle('sidebar-collapsed');
                  localStorage.setItem(
                      'sidebar-collapsed',
                      body.classList.contains('sidebar-collapsed')
                  );
              }
          });
      }

      if (closeBtn) {
          closeBtn.addEventListener('click', function () {
              body.classList.remove('sidebar-open');
          });
      }

      window.addEventListener('resize', function () {
          if (window.innerWidth >= 992) {
              body.classList.remove('sidebar-open');
          }
      });
  })();
  </script>

@include('layouts.confirm-modal')

<script>
(function () {
    const modalEl = document.getElementById('confirmModal');
    if (!modalEl) return;

    const bsModal   = new bootstrap.Modal(modalEl);
    const titleEl   = document.getElementById('confirmModalTitle');
    const subtitleEl= document.getElementById('confirmModalSubtitle');
    const msgEl     = document.getElementById('confirmModalMessage');
    const detailEl  = document.getElementById('confirmModalDetail');
    const iconWrap  = document.getElementById('confirmModalIconWrap');
    const iconEl    = document.getElementById('confirmModalIcon');
    const okBtn     = document.getElementById('confirmModalOk');

    let onConfirm = null;

    window.confirmAction = function (options) {
        const {
            title       = 'Konfirmasi',
            subtitle    = 'Tindakan ini tidak dapat dibatalkan',
            message     = 'Apakah Anda yakin?',
            detail      = null,
            confirmText = 'Ya, Lanjutkan',
            confirmClass= 'btn-danger',
            icon        = 'bi-exclamation-triangle-fill',
            iconColor   = 'danger', // danger | warning | primary | success
            onOk        = null,
        } = options;

        // Set isi
        titleEl.textContent    = title;
        subtitleEl.textContent = subtitle;
        msgEl.textContent      = message;

        if (detail) {
            detailEl.innerHTML = detail;
            detailEl.classList.remove('d-none');
        } else {
            detailEl.classList.add('d-none');
            detailEl.innerHTML = '';
        }

        // Icon & warna
        const colorMap = {
            danger:  { bg: 'rgba(220,53,69,.1)',  fg: '#dc3545' },
            warning: { bg: 'rgba(255,193,7,.15)', fg: '#ffc107' },
            primary: { bg: 'rgba(13,110,253,.1)', fg: '#0d6efd' },
            success: { bg: 'rgba(25,135,84,.1)',  fg: '#198754' },
        };
        const c = colorMap[iconColor] || colorMap.danger;

        iconWrap.style.background = c.bg;
        iconWrap.style.color      = c.fg;
        iconEl.className          = `bi ${icon} fs-4`;

        // Button OK
        okBtn.className  = `btn ${confirmClass}`;
        okBtn.innerHTML  = `<i class="bi bi-check-lg me-1"></i> ${confirmText}`;

        // Callback
        onConfirm = onOk;

        bsModal.show();
    };

    // Handle klik OK
    okBtn.addEventListener('click', function () {
        bsModal.hide();
        if (typeof onConfirm === 'function') {
            onConfirm();
        }
    });
})();
</script>

<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-delete');
    if (!btn) return;

    e.preventDefault();

    const form  = btn.closest('form');
    const nama  = btn.dataset.nama  || 'data ini';
    const title = btn.dataset.title || 'Hapus Data';

    confirmAction({
        title:        title,
        subtitle:     'Data akan dihapus permanen',
        message:      'Apakah Anda yakin ingin menghapus data ini?',
        detail:       `<i class="bi bi-info-circle me-1"></i> <strong>${nama}</strong>`,
        confirmText:  'Ya, Hapus',
        confirmClass: 'btn-danger',
        icon:         'bi-trash-fill',
        iconColor:    'danger',
        onOk:         () => form.submit()
    });
});
</script>
  @stack('scripts')
</body>
</html>