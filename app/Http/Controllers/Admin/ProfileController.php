<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show admin profile page.
     */
    public function index()
    {
        $admin = Auth::user();
        return view('admin.profile.index', compact('admin'));
    }

    /**
     * Show change password form.
     */
    public function password()
    {
        return view('admin.profile.password');
    }

    /**
     * Update profile information.
     */
    public function update(Request $request)
    {
        $admin = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);

        $admin->update($validated);

        return redirect()->route('admin.profile')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        $admin = Auth::user();
        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()->route('admin.profile')
            ->with('success', 'Password changed successfully.');
    }

    /**
     * Upload profile avatar via AJAX.
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $admin = Auth::user();

        try {
            if ($admin->avatar && Storage::disk('public')->exists('avatars/' . $admin->avatar)) {
                Storage::disk('public')->delete('avatars/' . $admin->avatar);
            }

            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
            $avatar->storeAs('avatars', $avatarName, 'public');

            $admin->avatar = $avatarName;
            $admin->save();

            return response()->json([
                'success' => true,
                'message' => 'Avatar updated successfully!',
                'avatar_url' => $admin->avatar_url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload avatar.',
            ], 500);
        }
    }

    /**
     * Remove uploaded avatar and restore default fallback.
     */
    public function removeAvatar(Request $request)
    {
        $admin = Auth::user();

        if ($admin->avatar && Storage::disk('public')->exists('avatars/' . $admin->avatar)) {
            Storage::disk('public')->delete('avatars/' . $admin->avatar);
        }

        $admin->avatar = null;
        $admin->save();

        return response()->json([
            'success' => true,
            'message' => 'Avatar removed successfully.',
            'avatar_url' => $admin->avatar_url,
        ]);
    }
}