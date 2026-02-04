@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')

    <header class="top-bar">
        <h2>Welcome back, **{{ Auth::user()->name ?? 'Learner' }}**! 👋</h2>
        <div class="user-info">
            <span class="level">Level {{ $language_level ?? 'A1' }}: {{
                match ($language_level ?? 'A1') {
                    'A1' => 'Beginner',
                    'A2' => 'Elementary',
                    'B1' => 'Intermediate',
                    'B2' => 'Upper Intermediate',
                    'C1' => 'Advanced',
                    'C2' => 'Proficient',
                    default => 'Unknown'
                }
            }}</span>
            <a href="#" class="profile-btn">👤</a>
        </div>
    </header>

    <section class="widgets-grid">

        <div class="card progress-card">
            <h3>🏆 Your Progress Snapshot</h3>
            <p>🔥 **Current Streak:** {{ $streak_days }} Days</p>
            <div class="stat">
                <span class="stat-label">Words Learned:</span>
                <span class="stat-value">{{ number_format($words_learned) }}</span>
            </div>
            <div class="stat">
                <span class="stat-label">Fluency Score:</span>
                <span class="stat-value">{{ number_format($fluency_score, 2) }}%</span>
            </div>
            <button class="view-details-btn">View Full Tracker</button>
        </div>

        <div class="card focus-card">
            @if ($daily_focus)
                <h3>🎯 Today's Focus: **{{ $daily_focus->topic }}**</h3>
                <p>Start your daily goal to earn **+20 XP**.</p>
                <div class="daily-goal">
                    <p>Complete **"{{ $daily_focus->title }}"** ({{ $daily_focus->estimated_time }} mins).</p>

                    <a href="{{ url('/lessons/' . $daily_focus->id) }}" class="start-btn-link">
                        <button class="start-btn">Start Lesson →</button>
                    </a>
                </div>
            @else
                <h3>🎯 Focus: You're all caught up!</h3>
                <p>You've completed all immediate recommendations. Launch the chatbot for practice!</p>
                <a href="{{ url('/chat') }}" class="chat-btn-link">
                    <button class="chat-btn">Launch AI Chatbot</button>
                </a>
            @endif
        </div>

        <div class="card practice-card">
            <h3>🗣️ Conversation Practice</h3>
            <p>Talk to our AI Chatbot about your weekend plans using your new vocabulary!</p>

            <a href="{{ url('/chat') }}" class="chat-btn-link">
                <button class="chat-btn">Launch AI Chatbot</button>
            </a>
        </div>

    </section>

    <section class="content-list">
        <h3>📚 Recommended for You</h3>

        @forelse ($recommendations as $recommendation)
            <div class="lesson-item">
                @php

                    $tagType = strtolower($recommendation->lesson->type ?? 'lesson');
                    $tagClass = match($tagType) {
                        'grammar' => 'tag-review',
                        'quiz' => 'tag-new',
                        'vocabulary' => 'tag-video',
                        default => 'tag-lesson',
                    };
                @endphp

                <span class="tag {{ $tagClass }}">{{ strtoupper($recommendation->lesson->type ?? 'LESSON') }}</span>
                **{{ $recommendation->lesson->topic ?? 'General' }}:** {{ $recommendation->lesson->title ?? 'N/A' }}

                <a href="{{ url('/lessons/' . $recommendation->lesson_id) }}">
                    <button>Start</button>
                </a>
            </div>
        @empty
            <p class="text-center p-4 bg-white rounded-lg shadow-sm">
                No new recommendations right now! You're making great progress. Try a Conversation Practice session.
            </p>
        @endforelse

    </section>

@endsection
