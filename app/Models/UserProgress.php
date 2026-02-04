<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserProgress extends Model
{
    use HasFactory;

    protected $table = 'user_progress';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // app/Models/UserProgress.php
    protected $fillable = [
        'user_id',
        'current_streak',
        'words_learned',
        'fluency_score',
        'language_level',
        'language',
        'module_type',
        'score',
        'activity_data',
        'completed_at'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'activity_data' => 'array',
        'completed_lessons' => 'array',
        'completed_at' => 'datetime',
    ];

    /**
     * Record user learning activity
     */
    public static function recordActivity($userId, $moduleType, $sentence, $score = null)
    {
        $language = explode('_', $moduleType)[0];

        return self::create([
            'user_id' => $userId,
            'language' => $language,
            'module_type' => $moduleType,
            'activity_data' => [
                'sentence' => $sentence,
                'timestamp' => now()->toDateTimeString()
            ],
            'score' => $score,
            'completed_at' => now()
        ]);
    }

    /**
     * Get user progress statistics
     */
    public static function getUserStats($userId)
    {
        $stats = self::where('user_id', $userId)
        ->whereNotNull('activity_data')
        ->selectRaw('language, module_type, COUNT(activity_data) as activity_count, AVG(score) as avg_score')
        ->groupBy('language', 'module_type')
        ->get();


        return $stats->groupBy('language')->map(function ($languageStats) {
            return [
                'total_activities' => $languageStats->sum('activity_count'),
                'average_score' => $languageStats->avg('avg_score'),
                'modules' => $languageStats->keyBy('module_type')
            ];
        });
    }

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate user streak
     */
    public static function calculateStreak($userId)
    {
        $recentActivities = self::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->get();

        $streak = 0;
        $currentDate = now();

        foreach ($recentActivities as $activity) {
            if ($activity->created_at->isToday() || $activity->created_at->isYesterday()) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }
}
