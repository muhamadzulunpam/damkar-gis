<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class WilayahService
{
    protected string $baseUrl = 'https://wilayah.smartartstudio.my.id/api';

    protected string $kodeTangsel = '36.74';

    protected string $cacheFile = 'geojson/kelurahan-tangsel.json';

    /**
     * Ambil GeoJSON FeatureCollection kelurahan Tangsel.
     * Kalau sudah pernah di-cache, langsung pakai file lokal
     * (tidak hit API luar lagi).
     */
    public function getTangselGeoJson(bool $forceRefresh = false): array
    {
        if (!$forceRefresh && Storage::exists($this->cacheFile)) {
            return json_decode(Storage::get($this->cacheFile), true);
        }

        $response = Http::timeout(30)->get(
            "{$this->baseUrl}/boundaries/{$this->kodeTangsel}/children"
        );

        if (!$response->successful()) {
            throw new \Exception(
                'Gagal mengambil data dari API wilayah: HTTP ' . $response->status()
            );
        }

        // Response API ini langsung berupa array, BUKAN {"data": [...]}
        $items = $response->json();

        $geoJson = $this->transformToGeoJson($items);

        Storage::put($this->cacheFile, json_encode($geoJson));

        return $geoJson;
    }

    /**
     * Ubah array datar dari API (campuran level kota/kecamatan/kelurahan)
     * menjadi GeoJSON FeatureCollection standar, hanya level kelurahan,
     * dengan property WADMKD & WADMKC (mengikuti pola front-end kamu).
     */
    protected function transformToGeoJson(array $items): array
    {
        // 1. Bangun kamus kode-kecamatan => nama-kecamatan
        //    Kode kecamatan punya 3 segmen, contoh: "36.74.01"
        $kecamatanLookup = [];

        foreach ($items as $item) {
            $segments = explode('.', $item['kode']);

            if (count($segments) === 3) {
                $kecamatanLookup[$item['kode']] = $item['nama'];
            }
        }

        // 2. Bangun Feature untuk tiap kelurahan (kode punya 4 segmen)
        $features = [];

        foreach ($items as $item) {
            $segments = explode('.', $item['kode']);

            if (count($segments) !== 4) {
                continue; // lewati level kota & kecamatan
            }

            $kecamatanKode = implode('.', array_slice($segments, 0, 3));
            $namaKecamatan = $kecamatanLookup[$kecamatanKode] ?? null;

            $geometry = $this->toGeoJsonGeometry($item['path'] ?? []);

            if (empty($geometry['coordinates'])) {
                continue; // lewati kalau tidak ada polygon sama sekali
            }

            $features[] = [
                'type' => 'Feature',
                'properties' => [
                    'WADMKD' => $item['nama'],
                    'WADMKC' => $namaKecamatan,
                    'KODE' => $item['kode'],
                ],
                'geometry' => $geometry,
            ];
        }

        return [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];
    }

    /**
     * Deteksi apakah path ini Polygon atau MultiPolygon berdasarkan
     * kedalaman array, lalu tukar urutan [lat,lng] -> [lng,lat]
     * (standar GeoJSON).
     */
    protected function toGeoJsonGeometry(array $path): array
    {
        if (empty($path)) {
            return ['type' => 'Polygon', 'coordinates' => []];
        }

        $depth = $this->arrayDepth($path);

        // depth 3: path = [ring, ring, ...]              -> Polygon
        // depth 4: path = [[ring,...], [ring,...], ...]  -> MultiPolygon (mis. kelurahan Rengas)
        $type = match ($depth) {
            3 => 'Polygon',
            4 => 'MultiPolygon',
            default => 'Polygon',
        };

        return [
            'type' => $type,
            'coordinates' => $this->swapLatLng($path),
        ];
    }

    protected function arrayDepth(array $arr): int
    {
        $maxDepth = 1;

        foreach ($arr as $value) {
            if (is_array($value)) {
                $depth = $this->arrayDepth($value) + 1;

                if ($depth > $maxDepth) {
                    $maxDepth = $depth;
                }
            }
        }

        return $maxDepth;
    }

    protected function isPoint(array $nested): bool
    {
        return count($nested) === 2
            && is_numeric($nested[0] ?? null)
            && is_numeric($nested[1] ?? null);
    }

    protected function swapLatLng(array $nested): array
    {
        if ($this->isPoint($nested)) {
            // [lat, lng] -> [lng, lat]
            return [$nested[1], $nested[0]];
        }

        return array_map(fn ($item) => $this->swapLatLng($item), $nested);
    }
}