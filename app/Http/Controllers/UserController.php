<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // ... your existing methods (Dashboard, Index, etc.) ...

    // =============================================
    // ADD THESE NEW METHODS FOR USER MANAGEMENT
    // =============================================

    /**
     * Display a listing of users (for admin)
     */
    public function adminIndex()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.view_users', compact('users'));
    }

    /**
     * Show the form for creating a new user (admin)
     */
    public function create()
    {
        return view('admin.add_users');
    }

    /**
     * Store a newly created user (admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
            'usertype' => 'user',

        ]);

        return redirect()->route('view_users')
            ->with('user_addMessage', 'User created successfully!');
    }

    /**
     * Show the form for editing the specified user (admin)
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit_user', compact('user'));
    }

    /**
     * Update the specified user in storage (admin)
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'usertype' => 'required|in:user,admin',
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        $validated = $request->validate($rules);

        // Update user
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->usertype = $validated['usertype'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('view_users')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user from storage (admin)
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->route('view_users')
                ->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('view_users')
            ->with('success', 'User deleted successfully!');
    }

    public function Dashboard()
    {
        if (auth()->check() && auth()->user()->usertype === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $user = auth()->user();

        $progress = $user->progress ?? $this->createInitialProgress($user->id);

        $userStats = $this->getUserProgressStats($user->id);

        $totalActivities = collect($userStats)->sum('total_activities');

        $wordsLearned = $this->calculateTotalWordsLearned($user->id);

        $lessonsCompleted = 0;
        $activeStreak = $progress->current_streak ?? 0;

        // language progress
        $hausaProgress = 0;
        $yorubaProgress = 0;
        $igboProgress = 0;

        foreach ($userStats as $stat) {
            if ($stat['language'] === 'hausa') $hausaProgress = $stat['total_activities'];
            if ($stat['language'] === 'yoruba') $yorubaProgress = $stat['total_activities'];
            if ($stat['language'] === 'igbo') $igboProgress = $stat['total_activities'];
        }

        $recentActivity = $this->getRecentActivity($user->id);
        $dailyGoalProgress = $this->getDailyGoalProgress($user->id);
        $recommendedNext = "Try Hausa to start learning!";
        $achievements = $this->getAchievements($wordsLearned);

        return view('dashboard', [
            'streak_days' => $activeStreak,
            'words_learned' => $wordsLearned,
            'fluency_score' => $progress->fluency_score ?? 10.00,
            'language_level' => $progress->language_level ?? 'A1',

            'totalActivities' => $totalActivities,
            'wordsLearned' => $wordsLearned,
            'lessonsCompleted' => $lessonsCompleted,
            'activeStreak' => $activeStreak,

            // Language-specific progress
            'hausaProgress' => $hausaProgress,
            'yorubaProgress' => $yorubaProgress,
            'igboProgress' => $igboProgress,

            // Content messages
            'recentActivity' => $recentActivity,
            'dailyGoalProgress' => $dailyGoalProgress,
            'recommendedNext' => $recommendedNext,
            'achievements' => $achievements,
        ]);
    }

    public function Index()
    {
        return redirect('/dashboard');
    }

    protected function getUserProgressStats($userId)
    {
        $languages = ['hausa', 'yoruba', 'igbo'];
        $stats = [];

        foreach ($languages as $language) {
            $activityCount = UserProgress::where('user_id', $userId)
                ->where('language', $language)
                ->count();

            $stats[] = [
                'language' => $language,
                'total_activities' => $activityCount,
                'progress' => min($activityCount * 25, 100),
            ];
        }

        return $stats;
    }

    protected function calculateTotalWordsLearned($userId)
    {
        $activities = UserProgress::where('user_id', $userId)->get();
        $totalWords = 0;

        foreach ($activities as $activity) {
            $activityData = $activity->activity_data ?? [];
            if (is_array($activityData) && isset($activityData['words_learned'])) {
                $totalWords += $activityData['words_learned'];
            }
        }

        return $totalWords;
    }

    protected function createInitialProgress($userId)
    {
        return UserProgress::create([
            'user_id' => $userId,
            'current_streak' => 0,
            'words_learned' => 0,
            'fluency_score' => 10.00,
            'language_level' => 'A1',
            'module_type' => 'initial',
            'language' => 'hausa',
            'score' => 0,
            'activity_data' => json_encode(['type' => 'initial_setup']),
        ]);
    }

    protected function getRecentActivity($userId)
    {
        $recentProgress = UserProgress::where('user_id', $userId)
            ->latest()
            ->first();

        if ($recentProgress) {
            $language = ucfirst($recentProgress->language ?? 'hausa');
            $moduleType = strtoupper($recentProgress->module_type ?? 'POS');
            return "You completed a {$language} {$moduleType} analysis session. Great progress!";
        }

        return "Start your first language learning session!";
    }

    protected function getDailyGoalProgress($userId)
    {
        $todayActivities = UserProgress::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->count();

        $remaining = max(0, 3 - $todayActivities);

        if ($remaining === 0) {
            return "Daily goal completed! 🎉";
        } elseif ($todayActivities === 0) {
            return "Complete 3 language analysis sessions today!";
        } else {
            return "Complete {$remaining} more session" . ($remaining > 1 ? 's' : '') . " today!";
        }
    }

    protected function getAchievements($wordsLearned)
    {
        if ($wordsLearned >= 50) {
            return "Mastered 50+ words across multiple languages!";
        } elseif ($wordsLearned >= 20) {
            return "Learned 20+ words in African languages!";
        } else {
            return "Getting started with African languages!";
        }
    }
}
