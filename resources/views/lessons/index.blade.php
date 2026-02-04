@extends('layouts.app')

@section('title', 'Lessons')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <!-- Filters Sidebar -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('lessons.index') }}">
                        <div class="mb-3">
                            <label class="form-label">Language</label>
                            <select name="language" class="form-select">
                                <option value="hausa" {{ $language == 'hausa' ? 'selected' : '' }}>Hausa</option>
                                <option value="yoruba" {{ $language == 'yoruba' ? 'selected' : '' }}>Yoruba</option>
                                <option value="igbo" {{ $language == 'igbo' ? 'selected' : '' }}>Igbo</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Level</label>
                            <select name="level" class="form-select">
                                <option value="beginner" {{ $level == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ $level == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ $level == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>

            <!-- Progress Summary -->
            @auth
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Your Progress</h5>
                </div>
                <div class="card-body">
                    <div class="progress mb-3" style="height: 20px;">
                        @php
                            $completedCount = count($userProgress);
                            $totalCount = $lessons->total();
                            $percentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                        @endphp
                        <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%;">
                            {{ $percentage }}%
                        </div>
                    </div>
                    <p class="mb-0">
                        <strong>{{ $completedCount }}</strong> of <strong>{{ $totalCount }}</strong> lessons completed
                    </p>
                </div>
            </div>
            @endauth
        </div>

        <div class="col-md-9">
            <!-- Lessons List -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ ucfirst($language) }} Lessons ({{ ucfirst($level) }})</h4>
                    @if(auth()->check() && auth()->user()->usertype === 'admin')
                    <a href="{{ route('lessons.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add New Lesson
                    </a>
                    @endif
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($lessons->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <h5>No lessons found</h5>
                            <p class="text-muted">Try changing your filters</p>
                        </div>
                    @else
                        <div class="row">
                            @foreach($lessons as $lesson)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 lesson-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title mb-0">{{ $lesson->title }}</h5>
                                            @if(in_array($lesson->id, $userProgress))
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> Completed
                                                </span>
                                            @endif
                                        </div>

                                        <p class="card-text text-muted">{{ $lesson->description }}</p>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div>
                                                <span class="badge bg-info me-2">{{ ucfirst($lesson->level) }}</span>
                                                <span class="text-muted">
                                                    <i class="fas fa-clock"></i> {{ $lesson->duration }} min
                                                </span>
                                            </div>

                                            <a href="{{ route('lessons.show', $lesson->id) }}" class="btn btn-primary btn-sm">
                                                Start Lesson
                                            </a>
                                        </div>
                                    </div>

                                    @if(auth()->check() && auth()->user()->usertype === 'admin')
                                    <div class="card-footer bg-transparent border-top-0 pt-0">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('lessons.edit', $lesson->id) }}" class="btn btn-outline-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Delete this lesson?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $lessons->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.lesson-card {
    transition: transform 0.3s, box-shadow 0.3s;
    border: 1px solid #e0e0e0;
}

.lesson-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.progress-bar {
    transition: width 0.6s ease;
}
</style>
@endsection
