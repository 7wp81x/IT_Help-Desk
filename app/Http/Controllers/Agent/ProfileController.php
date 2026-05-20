<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $agent = Auth::user();

        $stats = [
            'total_tickets' => $agent->assignedTickets()->count(),
            'open_tickets' => $agent->assignedTickets()->whereIn('status', ['open', 'in_progress', 'pending'])->count(),
            'resolved_tickets' => $agent->assignedTickets()->where('status', 'resolved')->count(),
            'comments_count' => $agent->comments()->count(),
        ];

        return view('agent.profile.index', compact('agent', 'stats'));
    }

    public function password()
    {
        return view('agent.profile.password');
    }

    public function update(Request $request)
    {
        $agent = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $agent->id,
            'phone' => ['nullable', 'string', 'max:20', 'philippine_phone'],
            'department_id' => 'nullable|exists:departments,id',
            'department' => 'nullable|string|max:255',
        ]);

        if (empty($request->input('department_id')) && $request->filled('department')) {
            $department = Department::where('name', $request->input('department'))->first();
            $agent->department_id = $department ? $department->id : null;
        } else {
            $agent->department_id = $request->input('department_id');
        }

        $agent->update($request->only(['name', 'email', 'phone']));

        return redirect()->route('agent.profile')
            ->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]+$/',
            ],
        ]);

        $agent = Auth::user();

        if (!Hash::check($request->current_password, $agent->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $agent->password = Hash::make($request->password);
        $agent->save();

        return redirect()->route('agent.profile')
            ->with('success', 'Password changed successfully.');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $agent = Auth::user();

        try {
            if ($agent->avatar && Storage::disk('public')->exists('avatars/' . $agent->avatar)) {
                Storage::disk('public')->delete('avatars/' . $agent->avatar);
            }

            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
            $avatar->storeAs('avatars', $avatarName, 'public');

            $agent->avatar = $avatarName;
            $agent->save();

            return response()->json([
                'success' => true,
                'message' => 'Avatar updated successfully!',
                'avatar_url' => $agent->avatar_url,
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
        $agent = Auth::user();

        if ($agent->avatar && Storage::disk('public')->exists('avatars/' . $agent->avatar)) {
            Storage::disk('public')->delete('avatars/' . $agent->avatar);
        }

        $agent->avatar = null;
        $agent->save();

        return response()->json([
            'success' => true,
            'message' => 'Avatar removed successfully.',
            'avatar_url' => $agent->avatar_url,
        ]);
    }
}
