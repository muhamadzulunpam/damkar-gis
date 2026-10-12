@push('styles')
<style>
    .badge-activity {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-activity-success {
        background-color: #e6f7ed;
        color: #1e7e42;
    }

    .badge-activity-secondary {
        background-color: #eef0f2;
        color: #5a6268;
    }

    .badge-activity-primary {
        background-color: #e7f0ff;
        color: #2563eb;
    }

    .badge-activity-warning {
        background-color: #fff8e1;
        color: #b8860b;
    }

    .badge-activity-danger {
        background-color: #fdecea;
        color: #c0392b;
    }

    .badge-activity-info {
        background-color: #e6f7fb;
        color: #1490a8;
    }

    .badge-activity-dark {
        background-color: #f1f2f4;
        color: #495057;
    }
</style>
@endpush

@extends('layouts.admin')

@section('title', 'Activity Log')

@section('content')


<div class="container-fluid">

    {{-- Header --}}
    <div class="mb-4">

        <h4 class="fw-bold mb-1">
            Activity Log
        </h4>

        <p class="text-muted mb-0">
            Riwayat aktivitas pengguna di dalam sistem.
        </p>

    </div>


    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('activity-log.index') }}"
            >

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-5">

                        <label class="form-label fw-semibold">
                            Pencarian
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari aktivitas, user, atau deskripsi..."
                            value="{{ request('search') }}"
                        >

                    </div>


                    {{-- Activity --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Aktivitas
                        </label>

                        <select
                            name="activity"
                            class="form-select"
                        >

                            <option value="">
                                Semua Aktivitas
                            </option>

                            @foreach($activities as $activity)

                                <option
                                    value="{{ $activity }}"
                                    @selected(
                                        request('activity') === $activity
                                    )
                                >
                                    {{ $activity }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Button --}}
                    <div class="col-md-3 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary me-2"
                        >

                            <i class="bi bi-search me-1"></i>

                            Filter

                        </button>


                        <a
                            href="{{ route('activity-log.index') }}"
                            class="btn btn-outline-secondary"
                        >

                            <i class="bi bi-arrow-counterclockwise"></i>

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Activity Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h6 class="fw-bold mb-0">

                <i class="bi bi-clock-history me-2"></i>

                Riwayat Aktivitas

            </h6>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="text-center">
                                #
                            </th>

                            <th>
                                User
                            </th>

                            <th>
                                Aktivitas
                            </th>

                            <th>
                                Deskripsi
                            </th>

                            <th>
                                Modul
                            </th>

                            <th>
                                IP Address
                            </th>

                            <th>
                                Waktu
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($logs as $index => $log)

                            <tr>

                                <td class="text-center">

                                    {{
                                        $logs->firstItem()
                                        + $index
                                    }}

                                </td>


                                <td>

                                    @if($log->user)

                                        <div class="fw-semibold">

                                            {{ $log->user->name }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $log->user->email }}

                                        </small>

                                    @else

                                        <span class="text-muted">
                                            System
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @php

                                        $badge = match(
                                            strtolower(
                                                $log->activity
                                            )
                                        ) {

                                            'login' =>
                                                'success',

                                            'logout' =>
                                                'secondary',

                                            'create',
                                            'menambah user' =>
                                                'primary',

                                            'update' =>
                                                'warning',

                                            'delete' =>
                                                'danger',

                                            'proses k-means' =>
                                                'info',

                                            default =>
                                                'dark',
                                        };

                                    @endphp


                                    <span class="badge-activity badge-activity-{{ $badge }}">
                                        {{ $log->activity }}
                                    </span>

                                </td>


                                <td>

                                    {{ $log->description }}

                                </td>


                                <td>

                                    @if($log->module)

                                        <span class="badge bg-light text-dark">

                                            {{ $log->module }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    <small>
                                        {{ $log->ip_address ?? '-' }}
                                    </small>

                                </td>


                                <td>

                                    <div>
                                        {{
                                            $log->created_at
                                                ->format('d/m/Y')
                                        }}
                                    </div>

                                    <small class="text-muted">

                                        {{
                                            $log->created_at
                                                ->format('H:i:s')
                                        }}

                                    </small>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5 text-muted"
                                >

                                    <i
                                        class="bi bi-clock-history fs-2 d-block mb-2"
                                    ></i>

                                    Belum ada aktivitas.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($logs->hasPages())

            <div class="card-footer bg-white">

                {{ $logs->links() }}

            </div>

        @endif

    </div>

</div>

@endsection