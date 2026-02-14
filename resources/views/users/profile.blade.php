<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IntelliLearn - Profile</title>
     <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
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
                <a href="{{ route('profile.show') }}" class="nav-item active">
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

            <a class="nav-item active">
                <div class="nav-icon">👤</div>
                <div class="nav-text">Profile</div>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-button">
                    <div class="nav-icon">🚪</div>
                    <div class="nav-text">Logout</div>
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="dashboard-header">
            <div class="header-left">
                <h1>Profile Settings</h1>
                <p>Manage your account settings and preferences</p>
            </div>
            <div class="header-right">
                <button class="header-btn btn-outline" onclick="window.location='{{ route('dashboard') }}'">Back to Dashboard</button>
            </div>
        </div>

        <div class="profile-container">
            @if(session('status') === 'profile-updated')
                <div class="alert alert-success">
                    Profile information updated successfully!
                </div>
            @endif

            @if(session('status') === 'password-updated')
                <div class="alert alert-success">
                    Password updated successfully!
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="profile-section">
                <div class="section-header">
                    <h2 class="section-title">Profile Information</h2>
                    <p class="section-description">Update your account's profile information and email address.</p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <button type="submit" class="save-button">SAVE</button>
                </form>
            </div>

            <div class="profile-section">
                <div class="section-header">
                    <h2 class="section-title">Update Password</h2>
                    <p class="section-description">Ensure your account is using a long, random password to stay secure.</p>
                </div>

                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label" for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-input" placeholder="Enter your current password" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">New Password</label>
                        <input type="password" id="password" name="password" class="form-input" placeholder="Enter your new password" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Confirm your new password" required>
                    </div>

                    <button type="submit" class="save-button">SAVE</button>
                </form>
            </div>

            <div class="profile-section danger-zone">
                <div class="section-header">
                    <h2 class="danger-title">Delete Account</h2>
                    <p class="danger-description">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                </div>

                <form method="POST" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="delete-button" onclick="confirmDelete()">DELETE ACCOUNT</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                document.getElementById('deleteAccountForm').submit();
            }
        }
    </script>
</body>
</html>
