<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
            ->latest();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'activity',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'description',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhereHas('user', function ($userQuery) use ($search) {

                    $userQuery
                        ->where(
                            'name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'email',
                            'like',
                            '%' . $search . '%'
                        );
                });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Activity
        |--------------------------------------------------------------------------
        */

        if ($request->filled('activity')) {

            $query->where(
                'activity',
                $request->activity
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $logs = $query
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Daftar aktivitas
        |--------------------------------------------------------------------------
        */

        $activities = ActivityLog::query()
            ->select('activity')
            ->distinct()
            ->orderBy('activity')
            ->pluck('activity');

        $title = 'Activity Log';
        return view(
            'admin.activity-logs.index',
            compact(
                'logs',
                'activities',
                'title'
            )
        );
    }
}