@extends('admin.layouts.master')

@section('title', 'View Users')

@section('content')

<div class="card">

    <div class="card-header">
        <div class="card-title">All Users</div>

        <div class="search-filter-container">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Search users...">
                <span class="search-icon">🔍</span>
            </div>

            <select class="filter-select">
                <option value="">All Users</option>
                <option value="admin">Admins</option>
            </select>
        </div>
    </div>

    <div class="card-body">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>

                        <td>
                            <div class="user-info-small">
                                <div class="user-avatar-small">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    <div style="font-size:12px;color:var(--gray);">
                                        {{ $user->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>{{ $user->email }}</td>

                        <td>
                            @if($user->usertype === 'admin')
                                <span class="badge badge-primary">Admin</span>
                            @else
                                <span class="badge badge-secondary">User</span>
                            @endif
                        </td>

                        <td>{{ $user->created_at->format('M d, Y') }}</td>

                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('edit_user', $user->id) }}" class="btn btn-warning btn-sm">
                                     Edit
                                </a>

                                <a href="{{ route('delete_user', $user->id) }}"
                                   class="btn btn-danger btn-sm delete-btn"
                                   data-user="{{ $user->name }}">
                                     Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:25px;">
                            No users found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $users->links() }}
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('.search-input');
    const filterSelect = document.querySelector('.filter-select');
    const rows = document.querySelectorAll('.table tbody tr');

    function filterTable() {
        const search = searchInput.value.toLowerCase();
        const filter = filterSelect.value;

        rows.forEach(row => {
            const name = row.cells[1].innerText.toLowerCase();
            const email = row.cells[2].innerText.toLowerCase();
            const type = row.cells[3].innerText.toLowerCase();
            const date = row.cells[4].innerText.toLowerCase();

            const matchSearch =
                name.includes(search) ||
                email.includes(search) ||
                date.includes(search);

            let matchFilter = true;
            if (filter === "admin") matchFilter = type.includes("admin");
            if (filter === "user") matchFilter = type.includes("user") && !type.includes("admin");

            row.style.display = (matchSearch && matchFilter) ? "" : "none";
        });
    }

    searchInput.addEventListener('input', filterTable);
    filterSelect.addEventListener('change', filterTable);

    // Delete confirmation
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e){
            const name = this.dataset.user;
            if(!confirm(`Are you sure you want to delete ${name}?`)){
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection
