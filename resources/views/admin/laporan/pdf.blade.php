<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kerawanan {{ $laporan['label'] }}</title>
    <style>
        @page { margin: 26px 28px 34px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #222; }
        h1 { font-size: 17px; margin: 0 0 5px; text-align: center; }
        h2 { font-size: 12px; margin: 18px 0 7px; }
        h3 { font-size: 10px; margin: 12px 0 6px; }
        p { margin: 3px 0; line-height: 1.45; }
        .muted { color: #666; }
        .center { text-align: center; }
        .meta { margin: 14px 0 18px; width: 100%; }
        .meta td { padding: 3px 5px; }
        table.data { width: 100%; border-collapse: collapse; margin: 6px 0 12px; }
        table.data th, table.data td { border: 1px solid #bbb; padding: 5px; vertical-align: top; }
        table.data th { background: #f0f2f5; font-weight: bold; }
        .right { text-align: right; }
        .pill { font-weight: bold; }
        .page-break { page-break-before: always; }
        .note { background: #f7f7f7; padding: 8px; border: 1px solid #ddd; }
        .footer { position: fixed; bottom: -20px; left: 0; right: 0; text-align: center; color: #777; font-size: 8px; }
    </style>
</head>
<body>
    <h1>LAPORAN ANALISIS TINGKAT KERAWANAN</h1>
    <p class="center"><strong>Dinas Pemadam Kebakaran dan Penyelamatan Kota Tangerang Selatan</strong></p>
    <p class="center">Periode analisis {{ $laporan['label'] }}</p>

    <table class="meta">
        <tr><td width="18%"><strong>Periode</strong></td><td>: {{ $laporan['awal'] }}–{{ $laporan['akhir'] }}</td><td width="18%"><strong>Tanggal cetak</strong></td><td>: {{ $laporan['dicetak_pada']?->format('d-m-Y H:i') ?? '-' }}</td></tr>
        <tr><td><strong>Dicetak oleh</strong></td><td colspan="3">: {{ $laporan['dicetak_oleh'] ?? '-' }}</td></tr>
    </table>

    <h2>1. Ringkasan hasil analisis</h2>
    <table class="data">
        <thead><tr><th>Jenis kejadian</th><th class="right">Kelurahan</th><th class="right">Total kejadian</th><th class="right">Tinggi</th><th class="right">Sedang</th><th class="right">Rendah</th><th>Status</th></tr></thead>
        <tbody>
        @foreach ($laporan['jenis'] as $data)
            <tr>
                <td>{{ $data['nama'] }}</td>
                <td class="right">{{ $data['ringkasan']['jumlah_kelurahan'] ?? 0 }}</td>
                <td class="right">{{ $data['ringkasan']['total_kejadian'] ?? 0 }}</td>
                <td class="right">{{ $data['ringkasan']['tinggi'] ?? 0 }}</td>
                <td class="right">{{ $data['ringkasan']['sedang'] ?? 0 }}</td>
                <td class="right">{{ $data['ringkasan']['rendah'] ?? 0 }}</td>
                <td>{{ $data['run'] ? 'Sudah dianalisis' : 'Belum ada hasil' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @foreach ($laporan['jenis'] as $data)
        @if ($data['run'])
            <h2>{{ $loop->iteration + 1 }}. Kelurahan dengan tingkat kerawanan tinggi — {{ $data['nama'] }}</h2>
            @if ($data['tinggi']->isEmpty())
                <p class="muted">Tidak terdapat kelurahan dengan kategori Tinggi pada hasil analisis ini.</p>
            @else
                <table class="data">
                    <thead><tr><th>No.</th><th>Kecamatan</th><th>Kelurahan</th><th class="right">Jumlah kejadian</th><th class="right">Kepadatan penduduk (jiwa/km²)</th></tr></thead>
                    <tbody>
                    @foreach ($data['tinggi'] as $row)
                        <tr><td>{{ $loop->iteration }}</td><td>{{ $row->kelurahan?->kecamatan?->nama ?? '-' }}</td><td>{{ $row->kelurahan?->nama ?? '-' }}</td><td class="right">{{ number_format((int) $row->jumlah_kejadian) }}</td><td class="right">{{ is_numeric($row->kepadatan_penduduk ?? null) ? number_format((float) $row->kepadatan_penduduk, 2, ',', '.') : '-' }}</td></tr>
                    @endforeach
                    </tbody>
                </table>
            @endif

            <h3>Daftar lengkap kelurahan — {{ $data['nama'] }}</h3>
            <table class="data">
                <thead><tr><th>No.</th><th>Kecamatan</th><th>Kelurahan</th><th class="right">Jumlah kejadian</th><th class="right">Kepadatan penduduk (jiwa/km²)</th><th>Tingkat kerawanan</th></tr></thead>
                <tbody>
                @foreach ($data['baris'] as $row)
                    <tr><td>{{ $loop->iteration }}</td><td>{{ $row->kelurahan?->kecamatan?->nama ?? '-' }}</td><td>{{ $row->kelurahan?->nama ?? '-' }}</td><td class="right">{{ number_format((int) $row->jumlah_kejadian) }}</td><td class="right">{{ is_numeric($row->kepadatan_penduduk ?? null) ? number_format((float) $row->kepadatan_penduduk, 2, ',', '.') : '-' }}</td><td>{{ $row->tingkat_kerawanan ?? '-' }}</td></tr>
                @endforeach
                </tbody>
            </table>

            <h3>Informasi evaluasi model</h3>
            <table class="data">
                <tbody>
                    <tr><th width="38%">Metode</th><td>K-Means</td></tr>
                    <tr><th>Jumlah cluster (k)</th><td>3</td></tr>
                    <tr><th>Silhouette Score</th><td>{{ $data['run']->getAttribute('silhouette_score') ?? $data['run']->getAttribute('silhouette') ?? 'Tidak tersedia pada data proses' }}</td></tr>
                    <tr><th>Davies-Bouldin Index</th><td>{{ $data['run']->getAttribute('davies_bouldin_score') ?? $data['run']->getAttribute('davies_bouldin_index') ?? $data['run']->getAttribute('dbi') ?? 'Tidak tersedia pada data proses' }}</td></tr>
                </tbody>
            </table>
        @endif
    @endforeach

    <h2>Catatan metode</h2>
    <div class="note">
        <p>Pengelompokan dilakukan menggunakan metode K-Means dengan tiga cluster yang disajikan sebagai tingkat kerawanan Tinggi, Sedang, dan Rendah. Hasil yang ditampilkan merupakan hasil analisis yang telah tersimpan pada sistem, bukan hasil perhitungan ulang saat laporan dibuat.</p>
        <p>Variabel ringkasan yang ditampilkan adalah jumlah kejadian dan kepadatan penduduk. Tingkat kerawanan bersifat relatif terhadap kelurahan lain pada periode yang dipilih dan perlu dipertimbangkan bersama kondisi lapangan saat digunakan untuk pengambilan keputusan.</p>
    </div>
    <div class="footer">Laporan Kerawanan · Periode {{ $laporan['label'] }}</div>
</body>
</html>
