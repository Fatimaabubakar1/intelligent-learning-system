<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lesson;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'reason',
        'priority',
        'completed_at',
    ];

    /**
     * Get the user who received the recommendation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lesson or content being recommended.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
