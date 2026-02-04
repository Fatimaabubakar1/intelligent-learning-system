@extends('admin.layouts.master')

@section('title', 'Edit User')

@section('content')

<a href="{{ route('view_users') }}" class="back-link">
    ← Back to Users List
</a>

<div class="add-users-container">
    <div class="form-card">

        <div class="card-header">
            <div class="card-title">Edit User Account</div>
        </div>

        <div class="card-body">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="margin:0;padding-left:18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Edit Form --}}
            <form action="{{ route('update_user', $user->id) }}" method="POST" class="add-user-form">
                @csrf
                @method('PUT')

                {{-- Full Name --}}
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') error @enderror"
                           value="{{ old('name', $user->name) }}"
                           placeholder="Enter full name" required>
                    @error('name') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email"
                           name="email"
                           class="form-control @error('email') error @enderror"
                           value="{{ old('email', $user->email) }}"
                           placeholder="Enter email" required>
                    @error('email') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                {{-- User Type --}}
                <div class="form-group">
                    <label class="form-label">User Type</label>
                    <select name="usertype"
                            class="form-control @error('usertype') error @enderror" required>
                        <option value="user"  {{ $user->usertype == 'user'  ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ $user->usertype == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('usertype') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label class="form-label">Password (optional)</label>
                    <input type="password"
                           name="password"
                           class="form-control @error('password') error @enderror"
                           placeholder="Enter new password or leave blank">
                    <span class="form-hint">Leave empty to keep existing password</span>
                    @error('password') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                           class="form-control" placeholder="Confirm new password">
                </div>

                {{-- Buttons --}}
                <div class="button-group">
                    <button type="submit" class="btn btn-primary btn-lg">
                        🔄 Update User
                    </button>
                    <a href="{{ route('view_users') }}" class="btn btn-secondary btn-lg">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.add-user-form');
    const pass = form.querySelector('input[name="password"]');
    const confirm = form.querySelector('input[name="password_confirmation"]');

    form.addEventListener('submit', function (e) {

        // Only validate if password entered
        if (pass.value && pass.value !== confirm.value) {
            e.preventDefault();
            alert('Passwords do not match!');
            return;
        }

        const btn = form.querySelector('button[type="submit"]');
        {{-- btn.innerHTML = "<span>⏳</span> Updating..."; --}}
        btn.disabled = true;
    });
});
</script>
@endsection
