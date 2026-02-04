<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <div class="logo-icon">🏠</div>
            <div class="logo-text">Admin Panel</div>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-title">Main Navigation</div>

        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <div class="nav-icon">📊</div><div class="nav-text">Dashboard</div>
        </a>

        <a href="{{ route('add_users') }}" class="nav-item {{ request()->routeIs('add_users') ? 'active' : '' }}">
            <div class="nav-icon">👥</div><div class="nav-text">Add Users</div>
        </a>

        <a href="{{ route('view_users') }}" class="nav-item {{ request()->routeIs('view_users') ? 'active' : '' }}">
            <div class="nav-icon">📋</div><div class="nav-text">View Users</div>
        </a>
    </div>

    <div class="nav-section logout-section">
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="nav-item logout-btn">
                <div class="nav-icon">🚪</div><div class="nav-text">Logout</div>
            </button>
        </form>
    </div>
</div>
