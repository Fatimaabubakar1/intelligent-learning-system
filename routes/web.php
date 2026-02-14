<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\LanguageDataController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ContactController;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

// Route::get('/contact', function () {
//     return view('contact');
// });

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Public Learning Routes (accessible to all)
Route::prefix('learning')->group(function () {
    Route::get('/hausa/pos', [LanguageDataController::class, 'showHausaPOS'])->name('learning.hausa.pos');
    Route::get('/yoruba/pos', [LanguageDataController::class, 'showYorubaPOS'])->name('learning.yoruba.pos');
    Route::get('/igbo/pos', [LanguageDataController::class, 'showIgboPOS'])->name('learning.igbo.pos');

    // Public lessons - accessible to all visitors
    Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/{id}', [LessonController::class, 'show'])->name('lessons.show');
});

// Language Data Routes (public)
Route::prefix('language-data')->group(function () {
    Route::get('/hausa', [LanguageDataController::class, 'showHausaData'])->name('language.hausa');
    Route::get('/igbo', [LanguageDataController::class, 'showIgboData'])->name('language.igbo');
    Route::get('/yoruba', [LanguageDataController::class, 'showYorubaData'])->name('language.yoruba');
    Route::post('/search', [LanguageDataController::class, 'searchLanguageData'])->name('language.search');
});

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'Dashboard'])->name('dashboard');
    Route::get('/index', [UserController::class, 'Index'])->name('index');
    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/update', [UserProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile/delete', [UserProfileController::class, 'destroy'])->name('profile.destroy');

    // Authenticated lesson actions
    Route::post('/lessons/{lesson}/complete', [LessonController::class, 'complete'])->name('lessons.complete');

    // Admin Routes
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/add_users', [AdminController::class, 'addUsers'])->name('add_users');
        Route::post('/add_users', [AdminController::class, 'postAddUsers'])->name('post_add_users');
        Route::get('/view_users', [AdminController::class, 'viewUsers'])->name('view_users');
        Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('edit_user');
        Route::put('/users/update/{id}', [UserController::class, 'update'])->name('update_user');
        Route::get('/delete_user/{id}', [AdminController::class, 'deleteUser'])->name('delete_user');

        // Admin-only lesson management
        Route::prefix('lessons')->group(function () {
            Route::get('/create', [LessonController::class, 'create'])->name('lessons.create');
            Route::post('/', [LessonController::class, 'store'])->name('lessons.store');
            Route::get('/{id}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
            Route::put('/{id}', [LessonController::class, 'update'])->name('lessons.update');
            Route::delete('/{id}', [LessonController::class, 'destroy'])->name('lessons.destroy');
        });
    });
});

// Test and Debug Routes
Route::get('/add-test-progress', function() {
    if (!Auth::check()) {
        return redirect('/login')->with('error', 'Please log in first.');
    }

    $user = Auth::user();

    UserProgress::create([
        'user_id' => $user->id,
        'language' => 'hausa',
        'module_type' => 'pos',
        'score' => 85,
        'activity_data' => json_encode([
            'type' => 'practice',
            'duration' => 15,
            'words_learned' => 5
        ]),
        'completed_at' => now(),
    ]);

    UserProgress::create([
        'user_id' => $user->id,
        'language' => 'yoruba',
        'module_type' => 'pos',
        'score' => 90,
        'activity_data' => json_encode([
            'type' => 'practice',
            'duration' => 12,
            'words_learned' => 3
        ]),
        'completed_at' => now(),
    ]);

    UserProgress::create([
        'user_id' => $user->id,
        'language' => 'igbo',
        'module_type' => 'ner',
        'score' => 78,
        'activity_data' => json_encode([
            'type' => 'practice',
            'duration' => 18,
            'words_learned' => 7
        ]),
        'completed_at' => now(),
    ]);

    return redirect('/dashboard')->with('success', 'Test progress data added!');
})->name('test.progress.data');

Route::get('/debug-table', function() {
    $columns = \Schema::getColumnListing('user_progress');
    $moduleTypeInfo = \DB::select("SHOW COLUMNS FROM user_progress LIKE 'module_type'");

    echo "Table columns: " . implode(', ', $columns) . "<br><br>";
    echo "Module_type column info: <pre>";
    print_r($moduleTypeInfo);
    echo "</pre>";

    $existingRecords = \App\Models\UserProgress::count();
    echo "Existing records: " . $existingRecords;
});

Route::get('/progress/test-data', [ProgressController::class, 'addTestData'])->name('progress.test.data');

// Route::get('/test-user', function() {
//     if (!Auth::check()) {
//         return "Not logged in";
//     }

//     $user = Auth::user();
//     return [
//         'name' => $user->name,
//         'email' => $user->email,
//         'usertype' => $user->usertype,
//         'is_admin' => $user->usertype === 'admin' ? 'YES' : 'NO'
//     ];
// });

// Route::get('/debug-user', function() {
//     if (!Auth::check()) {
//         return "Not logged in";
//     }

//     $user = Auth::user();
//     return response()->json([
//         'id' => $user->id,
//         'name' => $user->name,
//         'email' => $user->email,
//         'usertype' => $user->usertype,
//         'is_admin' => $user->usertype === 'admin',
//         'exact_usertype_value' => "'" . $user->usertype . "'"
//     ]);
// });

require __DIR__.'/auth.php';
