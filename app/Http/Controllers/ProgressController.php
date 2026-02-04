<?php

namespace App\Http\Controllers;

use App\Models\UserProgress;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    /**
     * Show the user's learning progress tracker.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/dashboard')->with('error', 'You must be logged in to view progress.');
        }

        $user = Auth::user();

        $userStats = $this->getUserProgressStats($user->id);

        $totalActivities = collect($userStats)->sum('total_activities');

        $totalLessons = Lesson::count();
        $lessonsCompleted = 0;

        $progressStats = [
            'totalLessons' => $totalLessons,
            'completedCount' => $lessonsCompleted,
            'completionPercentage' => 0,
            'completedLessonIds' => [],
        ];

        $progress = UserProgress::where('user_id', $user->id)->first();
        $wordsLearned = $progress ? $progress->words_learned : 0;
        $activeStreak = $progress ? $progress->current_streak : 0;
        $languageLevel = $progress ? $progress->language_level : 'A1';

        return view('progress.index', [
            'userStats' => $userStats,
            'totalActivities' => $totalActivities,
            'progressStats' => $progressStats,
            'wordsLearned' => $wordsLearned,
            'activeStreak' => $activeStreak,
            'languageLevel' => $languageLevel,
        ]);
    }

    /**
     * Get user progress statistics
     */
    protected function getUserProgressStats($userId)
    {
        $languages = ['hausa', 'yoruba', 'igbo'];
        $stats = [];

        foreach ($languages as $language) {
            $activityCount = UserProgress::where('user_id', $userId)
            ->where('language', $language)
            ->whereNotNull('activity_data')
            ->count();

            $stats[] = [
                'language' => $language,
                'total_activities' => $activityCount,
                'progress' => min($activityCount * 25, 100),
                'color' => $this->getLanguageColor($language),
                'icon' => $this->getLanguageIcon($language),
            ];
        }

        return $stats;
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
