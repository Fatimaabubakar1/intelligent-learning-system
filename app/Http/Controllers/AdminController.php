<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProgress;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'totalUsers' => User::count(),
            'activeUsers' => User::whereHas('progress')->count(),
            'totalActivities' => UserProgress::count(),
            'totalLessons' => Lesson::count(),
            'hausaActivities' => UserProgress::where('language', 'hausa')->count(),
            'yorubaActivities' => UserProgress::where('language', 'yoruba')->count(),
            'igboActivities' => UserProgress::where('language', 'igbo')->count(),
        ];

        $recentUsers = User::latest()->take(3)->get();

        $recentActivities = UserProgress::with('user')
            ->latest()
            ->take(3)
            ->get();

        $languageStats = [
            'hausa' => UserProgress::where('language', 'hausa')->count(),
            'yoruba' => UserProgress::where('language', 'yoruba')->count(),
            'igbo' => UserProgress::where('language', 'igbo')->count(),
        ];

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentActivities', 'languageStats'));
    }

    public function addUsers()
    {
        return view('admin.add_users');
    }

    public function postAddUsers(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->email_verified_at = now();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'User added successfully!');
    }

    public function viewUsers()
    {
        $users = User::orderBy('created_at', 'asc')->paginate(10);
        return view('admin.view_users', compact('users'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully!');
    }
}
