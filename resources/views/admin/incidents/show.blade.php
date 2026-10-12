@extends('layouts.admin')

@section('title', 'Detail Incident')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
            </span>
            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Detail Incident</h1>
                <p class="text-muted mb-0">
                    Informasi lengkap data incident kejadian.
                </p>
            </div>
        </div>
        <div class="heading-actions">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('incidents.index') }}">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Kembali
            </a>
            <a class="btn btn-primary btn-sm" href="{{ route('incidents.edit', $incident->id) }}">
                <i class="bi bi-pencil" aria-hidden="true"></i>
                Edit Incident
            </a>
        </div>
    </div>

    <section class="row g-3">
        <div class="col-12 col-xl-4">
            <div class="panel h-100 text-center profile-card">
                <div class="profile-cover">
                    <img src="{{ asset('img/damkar1.jpg') }}" alt="Incident cover preview">
                </div>
                <div class="profile-hero">
                    <div class="avatar-img avatar-xl profile-photo bg-danger bg-opacity-10 d-flex align-items-center justify-content-center">
                        <i class="bi bi-exclamation-triangle fs-1 text-danger" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="info-list mt-4 text-start">
                    <div>
                        <span>Alamat</span>
                        <strong>{{ $incident->alamat }}</strong>
                    </div>
                    <div>
                        <span>Kecamatan</span>
                        <strong>{{ $incident->kecamatan->nama ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>Kelurahan</span>
                        <strong>{{ $incident->kelurahan->nama ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>Jenis Kejadian</span>
                        <strong>{{ $incident->jenisKejadian->nama ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>Kategori Kejadian</span>
                        <strong>{{ $incident->kategoriKejadian->nama ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>Status</span>
                        <strong>
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
                            <span class="badge" style="background-color: {{ $colors['bg'] }}; color: {{ $colors['color'] }}; padding: 0.5rem 1rem;">
                                {{ $statusLabels[$incident->status] ?? $incident->status }}
                            </span>
                        </strong>
                    </div>
                    <div>
                        <span>Tanggal Kejadian</span>
                        <strong>{{ $incident->tanggal_kejadian ? \Carbon\Carbon::parse($incident->tanggal_kejadian)->format('d M Y') : '-' }}</strong>
                    </div>
                    <div>
                        <span>Waktu Kejadian</span>
                        <strong>{{ $incident->waktu_kejadian ?? '-' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            {{-- Informasi Detail --}}
            <div class="panel mb-3">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-info-circle" aria-hidden="true"></i>
                            <span>Informasi Lengkap Data Incident</span>
                        </h2>
                        <p class="text-muted mb-0">
                            Detail lengkap kejadian, korban, dan waktu pembuatan data.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('incidents.edit', $incident->id) }}">
                            <i class="bi bi-pencil-square" aria-hidden="true"></i>
                            Edit
                        </a>
                        <form action="{{ route('incidents.destroy', $incident->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus incident ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash" aria-hidden="true"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Data Korban --}}
                <div class="row g-3 px-4 py-3 border-bottom">
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3">
                            <i class="bi bi-people text-primary" aria-hidden="true"></i>
                            Data Korban
                        </h6>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded-3 text-center">
                            <p class="text-muted small mb-1">Total Korban</p>
                            <h4 class="mb-0 fw-bold text-primary">{{ $incident->jumlah_korban }}</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded-3 text-center">
                            <p class="text-muted small mb-1">Meninggal</p>
                            <h4 class="mb-0 fw-bold text-danger">{{ $incident->jumlah_meninggal }}</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded-3 text-center">
                            <p class="text-muted small mb-1">Luka-luka</p>
                            <h4 class="mb-0 fw-bold text-warning">{{ $incident->jumlah_luka }}</h4>
                        </div>
                    </div>
                </div>

                {{-- Detail Lainnya --}}
                <div class="row g-3 px-4 py-3 border-bottom">
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase">Estimasi Kerugian</label>
                        <p class="fw-semibold mb-0">Rp {{ number_format($incident->estimasi_kerugian, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase">Dilaporkan Oleh</label>
                        <p class="fw-semibold mb-0">{{ $incident->user->name ?? '-' }}</p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small fw-semibold text-uppercase">Deskripsi</label>
                        <p class="mb-0">{{ $incident->deskripsi ?? '-' }}</p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small fw-semibold text-uppercase">Keterangan</label>
                        <p class="mb-0">{{ $incident->keterangan ?? '-' }}</p>
                    </div>
                </div>

                {{-- Koordinat --}}
                <div class="row g-3 px-4 py-3 border-bottom">
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3">
                            <i class="bi bi-geo-alt text-primary" aria-hidden="true"></i>
                            Koordinat Lokasi
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase">Latitude</label>
                        <p class="fw-semibold mb-0">{{ number_format($incident->latitude, 7) }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase">Longitude</label>
                        <p class="fw-semibold mb-0">{{ number_format($incident->longitude, 7) }}</p>
                    </div>
                    <div class="col-12 mt-2">
                        <div 
                            id="map" 
                            style="height: 300px; width: 100%; border-radius: 8px; background: #e9ecef;"
                        ></div>
                    </div>
                </div>

                {{-- Aktivitas --}}
                <div class="activity-list">
                    <div class="activity-item">
                        <span class="activity-dot bg-primary"></span>
                        <div>
                            <p class="mb-1 fw-semibold">Data Incident Dibuat</p>
                            <p class="text-muted small mb-0">
                                {{ $incident->created_at->format('d M Y, H:i:s') }}
                                ({{ $incident->created_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>
                    <div class="activity-item">
                        <span class="activity-dot bg-success"></span>
                        <div>
                            <p class="mb-1 fw-semibold">Terakhir Diperbarui</p>
                            <p class="text-muted small mb-0">
                                {{ $incident->updated_at->format('d M Y, H:i:s') }}
                                ({{ $incident->updated_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>
                    @if($incident->created_at->eq($incident->updated_at))
                    <div class="activity-item">
                        <span class="activity-dot bg-warning"></span>
                        <div>
                            <p class="mb-1 fw-semibold">Status</p>
                            <p class="text-muted small mb-0">
                                <span class="badge" style="background-color: #fef3c7; color: #d97706;">
                                    <i class="bi bi-clock-history me-1"></i> Belum pernah diperbarui
                                </span>
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Leaflet CSS & JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    #map {
        cursor: default;
    }
    #map .leaflet-control-attribution {
        font-size: 9px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lat = {{ $incident->latitude ?? -6.2 }};
        const lng = {{ $incident->longitude ?? 106.8 }};

        // Inisialisasi peta
        const map = L.map('map').setView([lat, lng], 15);

        // Tile OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Marker (tidak bisa digeser)
        L.marker([lat, lng], {
            draggable: false
        }).addTo(map);

        // Optional: tambahkan popup dengan informasi
        L.marker([lat, lng], {
            draggable: false
        })
        .bindPopup(`
            <strong>{{ $incident->alamat }}</strong><br>
            {{ $incident->kecamatan->nama ?? '-' }}, {{ $incident->kelurahan->nama ?? '-' }}<br>
            <small>Lat: {{ number_format($incident->latitude, 7) }}, Lng: {{ number_format($incident->longitude, 7) }}</small>
        `)
        .openPopup();

        // Handle resize map
        setTimeout(() => {
            map.invalidateSize();
        }, 300);
    });
</script>
@endsection