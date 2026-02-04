@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-value">{{ $stats['totalUsers'] }}</div>
        <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card success">
        <div class="stat-icon">✅</div>
        <div class="stat-value">{{ $stats['activeUsers'] }}</div>
        <div class="stat-label">Active Users</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-icon">📊</div>
        <div class="stat-value">{{ $stats['totalActivities'] }}</div>
        <div class="stat-label">Total Activities</div>
    </div>
    <div class="stat-card danger">
        <div class="stat-icon">📚</div>
        <div class="stat-value">{{ $stats['totalLessons'] }}</div>
        <div class="stat-label">Total Lessons</div>
    </div>
</div>

<div class="dashboard-grid">

    <!-- Recent Activities -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Recent Activities</div>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="simple">User</th>
                            <th class="simple">Language</th>
                            <th class="simple">Activity</th>
                            <th class="simple">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentActivities as $activity)
                        <tr>
                            <td>{{ $activity->user->name }}</td>
                            <td>
                                <span class="badge badge-success" style="text-transform: capitalize;">
                                    {{ $activity->language }}
                                </span>
                            </td>
                            <td>{{ ucfirst($activity->module_type) }}</td>
                            <td>{{ $activity->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Language  -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Language Distribution</div>
        </div>
        <div class="card-body">
            <div class="language-stats">
                <div class="language-item">
                    <div class="language-name"><span>🇳🇬</span><span>Hausa</span></div>
                    <span class="language-count">{{ $languageStats['hausa'] }}</span>
                </div>
                <div class="language-item">
                    <div class="language-name"><span>🇳🇬</span><span>Yoruba</span></div>
                    <span class="language-count">{{ $languageStats['yoruba'] }}</span>
                </div>
                <div class="language-item">
                    <div class="language-name"><span>🇳🇬</span><span>Igbo</span></div>
                    <span class="language-count">{{ $languageStats['igbo'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users -->
<div class="card">
    <div class="card-header">
        <div class="card-title">Recent Users</div>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="simple">Name</th>
                        <th class="simple">Email</th>
                        <th class="simple">Joined</th>
                        <th class="simple">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentUsers as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm">View</button>
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
