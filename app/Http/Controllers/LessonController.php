<?php
// App\Http\Controllers\LessonController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * Display all lessons
     */
    public function index(Request $request)
    {
        $language = $request->get('language', 'hausa');
        $level = $request->get('level', 'beginner');

        $lessons = Lesson::active()
            ->byLanguage($language)
            ->byLevel($level)
            ->ordered()
            ->paginate(10);

        $userProgress = [];
        if (Auth::check()) {
            $progress = UserProgress::where('user_id', Auth::id())->first();

            if ($progress && isset($progress->completed_lessons)) {
                $userProgress = $progress->completed_lessons;
            }
        }

        return view('lessons.index', compact('lessons', 'language', 'level', 'userProgress'));
    }

    /**
     * Show a specific lesson
     */
    public function show($id)
    {
        $lesson = Lesson::findOrFail($id);

        // Get previous and next lessons
        $previousLesson = Lesson::where('language', $lesson->language)
            ->where('level', $lesson->level)
            ->where('order', '<', $lesson->order)
            ->orderBy('order', 'desc')
            ->first();

        $nextLesson = Lesson::where('language', $lesson->language)
            ->where('level', $lesson->level)
            ->where('order', '>', $lesson->order)
            ->orderBy('order', 'asc')
            ->first();

        // Check if user has completed this lesson
        $isCompleted = false;
        if (Auth::check()) {
            $progress = UserProgress::where('user_id', Auth::id())->first();

            if ($progress && isset($progress->completed_lessons)) {
                $isCompleted = in_array($lesson->id, $progress->completed_lessons);
            }
        }

        return view('lessons.show', compact('lesson', 'previousLesson', 'nextLesson', 'isCompleted'));
    }

    /**
     * Mark a specific lesson as complete for the authenticated user.
     */
    public function complete(Request $request, Lesson $lesson)
    {
        $user = Auth::user();
        $progress = UserProgress::firstOrCreate(
            ['user_id' => $user->id],
            ['completed_lessons' => []]
        );

        if (!in_array($lesson->id, $progress->completed_lessons)) {
            $completed = $progress->completed_lessons;
            $completed[] = $lesson->id;
            $progress->completed_lessons = $completed;
            $progress->save();

            return redirect()->back()->with('success', 'Lesson marked as complete!');
        }

        return redirect()->back()->with('info', 'Lesson already completed.');
    }

    /**
     * Show create lesson form (admin only)
     */
    public function create()
    {
        return view('lessons.create');
    }

    /**
     * Store new lesson (admin only)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|array',
            'language' => 'required|in:hausa,yoruba,igbo',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration' => 'required|integer|min:1',
            'order' => 'required|integer|min:0'
        ]);

        $lesson = Lesson::create([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'language' => $request->language,
            'level' => $request->level,
            'duration' => $request->duration,
            'order' => $request->order,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('lessons.show', $lesson->id)
            ->with('success', 'Lesson created successfully!');
    }

    /**
     * Show edit lesson form (admin only)
     */
    public function edit($id)
    {
        $lesson = Lesson::findOrFail($id);
        return view('lessons.edit', compact('lesson'));
    }

    /**
     * Update lesson (admin only)
     */
    public function update(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|array',
            'language' => 'required|in:hausa,yoruba,igbo',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration' => 'required|integer|min:1',
            'order' => 'required|integer|min:0'
        ]);

        $lesson->update([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'language' => $request->language,
            'level' => $request->level,
            'duration' => $request->duration,
            'order' => $request->order,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('lessons.show', $lesson->id)
            ->with('success', 'Lesson updated successfully!');
    }

    /**
     * Delete lesson (admin only)
     */
    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();

        return redirect()->route('lessons.index')
            ->with('success', 'Lesson deleted successfully!');
    }
}
