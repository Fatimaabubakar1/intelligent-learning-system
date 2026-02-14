@extends('layouts.app')

@section('title', $lesson->title)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('lessons.index') }}">Lessons</a></li>
            <li class="breadcrumb-item"><a href="{{ route('lessons.index', ['language' => $lesson->language, 'level' => $lesson->level]) }}">
                {{ ucfirst($lesson->language) }} - {{ ucfirst($lesson->level) }}
            </a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $lesson->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <!-- Lesson Content -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">{{ $lesson->title }}</h2>
                        <div>
                            <span class="badge bg-primary me-2">{{ ucfirst($lesson->level) }}</span>
                            <span class="badge bg-secondary">
                                <i class="fas fa-clock"></i> {{ $lesson->duration }} min
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h4>Description</h4>
                        <p class="lead">{{ $lesson->description }}</p>
                    </div>

                    <div class="lesson-content">
                        @foreach($lesson->content as $section)
                        <div class="mb-4">
                            <h4>{{ $section['title'] ?? 'Section' }}</h4>

                            @if(isset($section['type']) && $section['type'] == 'text')
                                <p>{{ $section['content'] }}</p>
                            @elseif(isset($section['type']) && $section['type'] == 'list')
                                <ul class="list-group mb-3">
                                    @foreach($section['items'] as $item)
                                    <li class="list-group-item">
                                        <i class="fas fa-check text-success me-2"></i>
                                        {{ $item }}
                                    </li>
                                    @endforeach
                                </ul>
                            @elseif(isset($section['type']) && $section['type'] == 'example')
                                <div class="card bg-light mb-3">
                                    <div class="card-header">
                                        <strong>Example:</strong>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-2"><strong>{{ $section['sentence'] }}</strong></p>
                                        <p class="text-muted mb-0">{{ $section['translation'] }}</p>
                                    </div>
                                </div>
                            @elseif(isset($section['type']) && $section['type'] == 'vocabulary')
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Word</th>
                                                <th>Meaning</th>
                                                <th>Part of Speech</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($section['words'] as $word)
                                            <tr>
                                                <td><strong>{{ $word['word'] }}</strong></td>
                                                <td>{{ $word['meaning'] }}</td>
                                                <td><span class="badge bg-info">{{ $word['pos'] }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Practice Exercise -->
                    @if(isset($lesson->content['exercise']))
                    <div class="card bg-light mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Practice Exercise</h5>
                        </div>
                        <div class="card-body">
                            <p>{{ $lesson->content['exercise']['question'] }}</p>

                            @if(isset($lesson->content['exercise']['type']) && $lesson->content['exercise']['type'] == 'multiple_choice')
                            <div class="mb-3">
                                @foreach($lesson->content['exercise']['options'] as $index => $option)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="exercise_answer" id="option{{ $index }}">
                                    <label class="form-check-label" for="option{{ $index }}">
                                        {{ $option }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @elseif(isset($lesson->content['exercise']['type']) && $lesson->content['exercise']['type'] == 'fill_blank')
                            <div class="mb-3">
                                <p>{{ $lesson->content['exercise']['sentence'] }}</p>
                                <input type="text" class="form-control" placeholder="Type your answer here...">
                            </div>
                            @endif

                            <button class="btn btn-primary" id="checkAnswer">Check Answer</button>
                            <div id="answerFeedback" class="mt-3" style="display: none;"></div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if($previousLesson)
                            <a href="{{ route('lessons.show', $previousLesson->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left"></i> Previous Lesson
                            </a>
                            @endif
                        </div>

                        <div>
                            @if(!$isCompleted)
                            <form action="{{ route('lessons.complete', $lesson->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Mark as Complete
                                </button>
                            </form>
                            @else
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-check-circle"></i> Completed
                            </span>
                            @endif
                        </div>

                        <div>
                            @if($nextLesson)
                            <a href="{{ route('lessons.show', $nextLesson->id) }}" class="btn btn-primary">
                                Next Lesson <i class="fas fa-arrow-right"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Lesson Info Sidebar -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Lesson Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Language:</span>
                            <strong>{{ ucfirst($lesson->language) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Level:</span>
                            <strong>{{ ucfirst($lesson->level) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Duration:</span>
                            <strong>{{ $lesson->duration }} minutes</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Lesson Order:</span>
                            <strong>#{{ $lesson->order }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Status:</span>
                            <span class="badge {{ $lesson->is_active ? 'bg-success' : 'bg-warning' }}">
                                {{ $lesson->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Your Progress:</span>
                            <span class="badge {{ $isCompleted ? 'bg-success' : 'bg-secondary' }}">
                                {{ $isCompleted ? 'Completed' : 'Not Started' }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Related Resources -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Related Resources</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('learning.hausa.pos') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-language me-2"></i> {{ ucfirst($lesson->language) }} POS Analyzer
                        </a>
                        <a href="{{ route('language.' . $lesson->language) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-book me-2"></i> {{ ucfirst($lesson->language) }} Dictionary
                        </a>
                        <a href="{{ route('learning.progress') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-line me-2"></i> Your Progress
                        </a>
                    </div>
                </div>
            </div>

            <!-- Admin Actions -->
            @if(auth()->check() && auth()->user()->usertype === 'admin')
            <div class="card mt-4 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Admin Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('lessons.edit', $lesson->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Lesson
                        </a>
                        <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this lesson?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> Delete Lesson
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAnswerBtn = document.getElementById('checkAnswer');
    const answerFeedback = document.getElementById('answerFeedback');

    if (checkAnswerBtn) {
        checkAnswerBtn.addEventListener('click', function() {
            // This is a simple example - you'd typically send this to your backend
            answerFeedback.innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    In a real implementation, this would check your answer and provide feedback.
                </div>
            `;
            answerFeedback.style.display = 'block';
        });
    }
});
</script>
@endsection
