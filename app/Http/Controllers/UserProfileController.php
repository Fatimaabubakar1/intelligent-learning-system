<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    /**
     * Display the user's profile page.
     */
    public function show()
    {
        $user = Auth::user();

        $totalActivities = \App\Models\UserProgress::where('user_id', $user->id)->count();

        $hausaProgress = \App\Models\UserProgress::where('user_id', $user->id)
                        ->where('language', 'hausa')
                        ->count();

        $yorubaProgress = \App\Models\UserProgress::where('user_id', $user->id)
                        ->where('language', 'yoruba')
                        ->count();

        $igboProgress = \App\Models\UserProgress::where('user_id', $user->id)
                        ->where('language', 'igbo')
                        ->count();

        return view('users.profile', [
            'user' => $user,
            'totalActivities' => $totalActivities,
            'hausaProgress' => $hausaProgress,
            'yorubaProgress' => $yorubaProgress,
            'igboProgress' => $igboProgress,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return back()->with('status', 'profile-updated')->with('message', 'Profile information updated successfully!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated')->with('message', 'Password updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'Your account has been deleted successfully.');
    }
}
