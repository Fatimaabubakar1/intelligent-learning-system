@extends('layouts.app')

@section('title', 'My Learning Progress')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="text-4xl font-extrabold text-gray-900">Your Learning Journey</h1>
            <p class="text-lg text-gray-600">Track your progress and stay motivated!</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 mb-4">
            <div class="card shadow-lg border-0 rounded-xl overflow-hidden">
                <div class="card-body p-5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <i class="fas fa-chart-line fa-3x opacity-75"></i>
                        <h3 class="text-3xl font-bold">Overall Completion</h3>
                    </div>

                    <div class="my-4">
                        <div class="text-6xl font-extrabold">{{ $progressStats['completionPercentage'] }}%</div>
                        <p class="mt-2 text-sm opacity-90">
                            {{ $progressStats['completedCount'] }} out of {{ $progressStats['totalLessons'] }} lessons completed.
                        </p>
                    </div>

                    <div class="progress h-2" role="progressbar" aria-label="Progress" aria-valuenow="{{ $progressStats['completionPercentage'] }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-white shadow-sm" style="width: {{ $progressStats['completionPercentage'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-8 col-lg-9">
            <div class="card shadow-md border-0">
                <div class="card-header bg-white border-bottom p-4">
                    <h4 class="font-semibold text-gray-800">Completed Lesson IDs (For Reference)</h4>
                </div>
                <div class="card-body p-4">
                    @if(count($progressStats['completedLessonIds']) > 0)
                        <div class="row">
                            @foreach($progressStats['completedLessonIds'] as $lessonId)
                                <div class="col-6 col-sm-4 col-md-3 mb-2">
                                    <span class="badge bg-success text-white py-2 px-3 rounded-pill shadow-sm">
                                        <i class="fas fa-check-circle mr-1"></i> ID: {{ $lessonId }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info text-center" role="alert">
                            <i class="fas fa-lightbulb mr-2"></i>
                            It looks like you haven't completed any lessons yet! Start your first lesson to see your progress here.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
