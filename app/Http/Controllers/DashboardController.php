<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\UserProgress;
use App\Models\Lesson;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the user's intelligence learning dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Ensure user has progress
        $progress = $user->progress ?? $this->createInitialProgress($user->id);

        // Get user stats using the same method as ProgressController
        $userStats = $this->getUserProgressStats($user->id);

        // Calculate totals from the stats
        $totalActivities = collect($userStats)->sum('total_activities');

        // Get words learned from activity_data since we're storing it there
        $wordsLearned = $this->calculateTotalWordsLearned($user->id);

        $lessonsCompleted = 0; // Since we don't have recommendations table
        $activeStreak = $progress->current_streak ?? 0;

        // Get language-specific progress from the stats
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
        $recommendedNext = "Try Hausa POS analysis to start learning!";
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

            'hausaProgress' => $hausaProgress,
            'yorubaProgress' => $yorubaProgress,
            'igboProgress' => $igboProgress,

            'recentActivity' => $recentActivity,
            'dailyGoalProgress' => $dailyGoalProgress,
            'recommendedNext' => $recommendedNext,
            'achievements' => $achievements,
        ]);
    }

    /**
     * Get user progress statistics (same as ProgressController)
     */
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
                'progress' => min($activityCount * 25, 100), // Simple progress calculation
                'color' => $this->getLanguageColor($language),
                'icon' => $this->getLanguageIcon($language),
            ];
        }

        return $stats;
    }

    /**
     * Calculate total words learned from activity_data
     */
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

    /**
     * Helper function to ensure every new user has a progress record
     */
    protected function createInitialProgress($userId)
    {
        return UserProgress::create([
            'user_id' => $userId,
            'current_streak' => 0,
            'words_learned' => 0,
            'fluency_score' => 10.00,
            'language_level' => 'A1',
        ]);
    }

    /**
     * Get recent activity message
     */
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

    /**
     * Get daily goal progress message
     */
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

    /**
     * Get achievements message
     */
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

    /**
     * Get color for language
     */
    protected function getLanguageColor($language)
    {
        $colors = [
            'hausa' => '#3b82f6',
            'yoruba' => '#10b981',
            'igbo' => '#f59e0b',
        ];

        return $colors[$language] ?? '#4f46e5';
    }

    /**
     * Get icon for language
     */
    protected function getLanguageIcon($language)
    {
        $icons = [
            'hausa' => '🔤',
            'yoruba' => '📝',
            'igbo' => '🏷️',
        ];

        return $icons[$language] ?? '📚';
    }
}
