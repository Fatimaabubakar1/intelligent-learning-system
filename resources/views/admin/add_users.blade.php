@extends('admin.layouts.master')

@section('title', 'Add Users')

@section('content')

<a href="{{ route('admin.dashboard') }}" class="back-link">
    ← Back to Dashboard
</a>

<div class="add-users-container">
    <div class="form-card">
        <div class="card-header">
            <div class="card-title">Create New User Account</div>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('user_addMessage'))
                <div class="alert alert-success">{{ session('user_addMessage') }}</div>
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

            <form action="{{ route('post_add_users') }}" method="POST" class="add-user-form">
                @csrf

                {{-- Full Name --}}
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name"
                        class="form-control @error('name') error @enderror"
                        placeholder="Enter user's full name"
                        value="{{ old('name') }}" required>
                    @error('name') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email"
                        class="form-control @error('email') error @enderror"
                        placeholder="Enter user's email" value="{{ old('email') }}" required>
                    @error('email') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password"
                        class="form-control @error('password') error @enderror"
                        placeholder="Create a secure password" required>
                    <span class="form-hint">Minimum 8 characters with letters and numbers</span>
                    @error('password') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        class="form-control" placeholder="Confirm password" required>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary btn-lg">
                        👤 Create User Account
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-lg">
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
    const inputs = form.querySelectorAll('.form-control');

    inputs.forEach(input => {
        input.addEventListener('focus', () => input.parentElement.classList.add('focused'));
        input.addEventListener('blur', () => input.parentElement.classList.remove('focused'));
    });

    form.addEventListener('submit', function () {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '⏳ Creating User...';
        submitBtn.disabled = true;
    });
});
</script>
@endsection
