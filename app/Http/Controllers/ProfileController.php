<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan profile dan aktivitas user yang sedang login.
     */
    public function index()
    {
        $user = auth()->user();

        $activities = $user->activityLogs()
            ->latest()
            ->paginate(10, ['*'], 'activity_page');

        $title = 'Profile';

        return view('admin.profile.index', compact(
            'user',
            'activities',
            'title'
        ));
    }

    /**
     * Update profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $user->update($validated);

        ActivityLogger::log(
            'Update Profile',
            'User memperbarui informasi profil.',
            'Profile',
            'update'
        );

        return back()->with(
            'success',
            'Profil berhasil diperbarui.'
        );
    }

    /**
     * Update password.
     */
    public function password(Request $request)
    {
        $validated = $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $user = auth()->user();

        $user->update([
            'password' => Hash::make(
                $validated['password']
            ),
        ]);

        ActivityLogger::log(
            'Change Password',
            'User berhasil mengubah password.',
            'Profile',
            'update'
        );

        return back()->with(
            'success',
            'Password berhasil diubah.'
        );
    }
}