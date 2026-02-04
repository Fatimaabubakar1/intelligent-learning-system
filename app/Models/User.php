<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function progress()
    {
        return $this->hasOne(UserProgress::class);
    }

    public function isAdmin()
    {
        return $this->is_admin === 1 || $this->is_admin === true;
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->progress()->create([
                'current_streak' => 0,
                'words_learned' => 0,
                'fluency_score' => 10.00,
                'language_level' => 'A1',
                'module_type' => 'initial',
                'language' => 'hausa',
                'activity_data' => json_encode([
                    'type' => 'initial_setup',
                    'words_learned' => 0,
                    'timestamp' => now()->toDateTimeString()
                ]),
                'score' => 0,
                'completed_at' => now()
            ]);
        });
    }
}
