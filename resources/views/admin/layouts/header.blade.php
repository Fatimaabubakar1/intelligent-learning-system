<div class="header">
    <h1>@yield('title')</h1>

    <div class="user-menu">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div>
                <div style="font-weight: 600;">{{ Auth::user()->name }}</div>
                <div style="font-size: 12px; color: gray;">Administrator</div>
            </div>
        </div>
    </div>
</div>
