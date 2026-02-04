<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Tracker - IntelliLearn</title>
    <link rel="stylesheet" href="{{ asset('css/progress.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="progress-container">
        <div class="progress-header">
            <h1>Your Learning Progress</h1>
            <p>Track your journey in mastering African languages</p>
        </div>

        <div class="stats-overview">
            <div class="stat-card">
                <div class="stat-value">{{ $totalActivities ?? 0 }}</div>
                <div class="stat-label">Total Activities</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $progressStats['completedCount'] ?? 0 }}</div>
                <div class="stat-label">Lessons Completed</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $progressStats['completionPercentage'] ?? 0 }}%</div>
                <div class="stat-label">Overall Progress</div>
            </div>
        </div>

        <div class="language-progress">
            @if(isset($userStats) && count($userStats) > 0)
                @foreach($userStats as $stat)
                    <div class="language-card">
                        <div class="language-header">
                            <div class="language-icon" style="background: {{ $stat['color'] ?? '#4f46e5' }};">
                                {{ $stat['icon'] ?? '📚' }}
                            </div>
                            <div>
                                <div class="language-title">{{ ucfirst($stat['language'] ?? 'Unknown') }}</div>
                                <div class="language-subtitle">{{ $stat['total_activities'] ?? 0 }} Activities</div>
                            </div>
                        </div>

                        <div class="progress-bar-container">
                            <div class="progress-bar"
                                 style="width: {{ min($stat['progress'] ?? 0, 100) }}%; background: {{ $stat['color'] ?? '#4f46e5' }};"></div>
                        </div>

                        <div class="progress-details">
                            <span>{{ number_format(min($stat['progress'] ?? 0, 100), 1) }}% Complete</span>
                            <span>{{ $stat['total_activities'] ?? 0 }} Activities</span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="language-card">
                    <div class="language-header">
                        <div>No progress data available yet. Start learning to see your progress!</div>
                    </div>
                </div>
            @endif
        </div>

        <a href="{{ route('dashboard') }}" class="back-button">← Back to Dashboard</a>
    </div>
</body>
</html>
