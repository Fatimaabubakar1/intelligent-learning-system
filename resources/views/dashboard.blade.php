<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IntelliLearn - Language Acquisition</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <div class="logo-icon">🧠</div>
            <div class="logo-text">IntelliLearn</div>
        </div>

        <div class="progress-card">
            <div class="progress-title">Weekly Progress</div>
            <div class="progress-count">{{ $totalActivities ?? 0 }} activities</div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ min(($totalActivities ?? 0) * 5, 100) }}%"></div>
            </div>
        </div>

        <div class="nav-section">
            <div class="nav-group">
                <div class="nav-title">AI Learning</div>
                <a href="{{ route('learning.hausa.pos') }}" class="nav-item">
                    <div class="nav-icon">🔤</div>
                    <div class="nav-text">Hausa</div>
                </a>
                <a href="{{ route('learning.yoruba.pos') }}" class="nav-item">
                    <div class="nav-icon">📝</div>
                    <div class="nav-text">Yoruba</div>
                </a>
                <a href="{{ route('learning.igbo.pos') }}" class="nav-item">
                    <div class="nav-icon">🏷️</div>
                    <div class="nav-text">Igbo</div>
                </a>
            </div>

            <div class="nav-group">
                <div class="nav-title">Account</div>
                <a href="{{ route('progress.index')}}" class="nav-item">
                    <div class="nav-icon">📊</div>
                    <div class="nav-text">Progress Tracker</div>
                </a>
                <a href="{{ route('profile.show') }}" class="nav-item">
                    <div class="nav-icon">⚙️</div>
                    <div class="nav-text">Settings</div>
                </a>
            </div>
        </div>

        <div class="user-section">
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="user-details">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-level">Language Learner</div>
                </div>
            </div>

                <a class="user-info">
                <div class="nav-icon">👤</div>
                <div class="nav-text">Profile</div>
                </a>

            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-button" onclick="event.preventDefault(); this.closest('form').submit();">
                    <div class="nav-icon">🚪</div>
                    <div class="nav-text">Logout</div>
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="dashboard-header">
            <div class="header-left">
                <h1>Welcome back, {{ Auth::user()->name }}</h1>
                <p>Master African languages with AI-powered learning</p>
            </div>
            <div class="header-right">
                <button class="header-btn btn-outline">Today's Goals</button>
                <button class="header-btn btn-primary" onclick="window.location='{{ route('learning.hausa.pos') }}'">Start Learning</button>
            </div>
        </div>

        <div class="learning-modules">
            <h2 style="margin-bottom: 20px; color: #1f2937;">AI-Powered Language Learning</h2>
            <div class="modules-grid">
                <div class="module-card hausa">
                    <div class="module-header">
                        <div class="module-icon hausa">
                            <i class="fas fa-language"></i>
                        </div>
                        <div>
                            <div class="module-title">Hausa</div>
                            <div class="module-subtitle">Part-of-Speech Analysis</div>
                        </div>
                    </div>
                    <div class="module-description">
                        Analyze Hausa sentences with AI-powered part-of-speech tagging using our comprehensive Hausa dictionary.
                    </div>
                    <div class="module-features">
                        <div class="feature">
                            <span class="feature-icon">✓</span>
                            Real-time sentence analysis
                        </div>
                        <div class="feature">
                            <span class="feature-icon">✓</span>
                            Detailed POS explanations
                        </div>
                        <div class="feature">
                            <span class="feature-icon">✓</span>
                            Progress tracking
                        </div>
                    </div>
                    <a href="{{ route('learning.hausa.pos') }}" class="module-btn hausa">
                        Start Learning Hausa
                    </a>
                    <div class="progress-stats">
                        <span>Activities: {{ $hausaProgress ?? 0 }}</span>
                    </div>
                </div>

                <div class="module-card yoruba">
                    <div class="module-header">
                        <div class="module-icon yoruba">
                            <i class="fas fa-spell-check"></i>
                        </div>
                        <div>
                            <div class="module-title">Yoruba</div>
                            <div class="module-subtitle">Part of Speech Learning</div>
                        </div>
                    </div>
                    <div class="module-description">
                        Analyze Yoruba sentences with AI-powered part-of-speech tagging using our curated Yoruba dictionary.
                    </div>
                    <div class="module-features">
                        <div class="feature">
                            <span class="feature-icon">✓</span>
                            Grammar structure breakdown
                        </div>
                        <div class="feature">
                            <span class="feature-icon">✓</span>
                            Part-of-speech explanations
                        </div>
                        <div class="feature">
                            <span class="feature-icon">✓</span>
                            Language learning support
                        </div>
                    </div>
                    <a href="{{ route('learning.yoruba.pos') }}" class="module-btn yoruba">
                        Start Learning Yoruba
                    </a>
                    <div class="progress-stats">
                        <span>Activities: {{ $yorubaProgress ?? 0 }}</span>
                    </div>
                </div>

                <div class="module-card igbo">
                    <div class="module-header">
                        <div class="module-icon igbo">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div>
                            <div class="module-title">Igbo</div>
                            <div class="module-subtitle">Part of Speech Analysis</div>
                        </div>
                    </div>
                    <div class="module-description">
                        Analyze Igbo sentences with AI-powered part-of-speech tagging using our comprehensive Igbo dictionary.
                    </div>
                    <div class="module-features">
                        <div class="feature">
                            <span class="feature-icon">✓</span>
                           Real-time grammatical analysis
                        </div>
                        <div class="feature">
                            <span class="feature-icon">✓</span>
                            Detailed POS explanations
                        </div>
                        <div class="feature">
                            <span class="feature-icon">✓</span>
                            Native speaker validation
                        </div>
                    </div>
                    <a href="{{ route('learning.igbo.pos') }}" class="module-btn igbo">
                        Start Learning Igbo
                    </a>
                    <div class="progress-stats">
                        <span>Activities: {{ $igboProgress ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $totalActivities ?? 0 }}</div>
                <div class="stat-label">Total Activities</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $wordsLearned ?? 0 }}</div>
                <div class="stat-label">Words Learned</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $lessonsCompleted ?? 0 }}</div>
                <div class="stat-label">Lessons Completed</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $activeStreak ?? 0 }}</div>
                <div class="stat-label">Active Streak</div>
            </div>
        </div>

        <div class="content-grid">
            <div class="content-card">
                <h3 class="card-title">
                    <span class="card-title-icon">📚</span>
                    Recent Activity
                </h3>
                <p class="card-content">
                    @if($recentActivity ?? false)
                        {{ $recentActivity }}
                    @else
                        You completed a Hausa POS analysis session. Great progress!
                    @endif
                </p>
            </div>
            <div class="content-card">
                <h3 class="card-title">
                    <span class="card-title-icon">🎯</span>
                    Daily Goal
                </h3>
                <p class="card-content">
                    @if($dailyGoalProgress ?? false)
                        {{ $dailyGoalProgress }}
                    @else
                        Complete 3 language analysis sessions today!
                    @endif
                </p>
            </div>
            <div class="content-card">
                <h3 class="card-title">
                    <span class="card-title-icon">🔄</span>
                    Recommended Next
                </h3>
                <p class="card-content">
                    @if($recommendedNext ?? false)
                        {{ $recommendedNext }}
                    @else
                        Try Yoruba POS analysis to expand your skills!
                    @endif
                </p>
            </div>
            <div class="content-card">
                <h3 class="card-title">
                    <span class="card-title-icon">🏆</span>
                    Achievements
                </h3>
                <p class="card-content">
                    @if($achievements ?? false)
                        {{ $achievements }}
                    @else
                        Mastered basic POS tagging in Hausa!
                    @endif
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
