<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo_damkar.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: system-ui, -apple-system, sans-serif;
            padding: 1.5rem;
        }

        .error-card {
            max-width: 520px;
            width: 100%;
            border: 0;
            border-radius: 1.25rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            background: #fff;
        }

        .error-icon-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            margin-bottom: 1.25rem;
        }

        .error-code {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #dc3545, #b02a37);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-home {
            border-radius: 999px;
            padding: .6rem 1.5rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="error-card">
        <div class="card-body text-center p-5">

            <div class="error-icon-wrap">
                <i class="bi bi-shield-lock" style="font-size: 2.75rem;"></i>
            </div>

            <div class="error-code mb-2">403</div>

            <h4 class="fw-bold mb-2">Akses Ditolak</h4>

            <p class="text-muted mb-4">
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
                Silakan hubungi administrator jika menurut Anda ini sebuah kesalahan.
            </p>

            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-home">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-home">
                    <i class="bi bi-house-door me-1"></i> Dashboard
                </a>
            </div>

        </div>
    </div>

</body>
</html>