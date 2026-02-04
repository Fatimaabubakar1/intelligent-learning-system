@section('content')
<div class="container mx-auto py-10 px-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Welcome back, {{ Auth::user()->name }} 👋</h1>
        <p class="text-gray-600">Your current level: <span class="font-semibold">{{ $language_level }}</span></p>
    </div>

    <div class="bg-white shadow-lg rounded-2xl p-6 mb-8 border-l-4 border-blue-500">
        <h2 class="text-xl font-semibold mb-3">🎯 Today's Focus</h2>

        @if($daily_focus)
            <p class="text-lg font-medium text-gray-800">{{ $daily_focus->title }}</p>
            <p class="text-gray-600 mt-1">Topic: {{ $daily_focus->topic }}</p>
            <p class="text-gray-600">Type: {{ $daily_focus->type }}</p>
            <p class="text-gray-600">Estimated Time: {{ $daily_focus->estimated_time }} mins</p>
            <a href="#" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Start Lesson</a>
        @else
            <p class="text-gray-600">You’ve completed all available lessons for this level. 🎉</p>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-50 p-6 rounded-2xl shadow-sm">
            <h3 class="text-lg font-semibold text-blue-700">🔥 Current Streak</h3>
            <p class="text-2xl font-bold mt-2">{{ $streak_days }} days</p>
        </div>

        <div class="bg-green-50 p-6 rounded-2xl shadow-sm">
            <h3 class="text-lg font-semibold text-green-700">🧠 Words Learned</h3>
            <p class="text-2xl font-bold mt-2">{{ $words_learned }}</p>
        </div>

        <div class="bg-purple-50 p-6 rounded-2xl shadow-sm">
            <h3 class="text-lg font-semibold text-purple-700">💬 Fluency Score</h3>
            <p class="text-2xl font-bold mt-2">{{ $fluency_score }}%</p>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-2xl p-6 border-l-4 border-purple-500">
        <h2 class="text-xl font-semibold mb-4">📚 Recommended Lessons</h2>

        @if($recommendations->isNotEmpty())
            <ul class="space-y-3">
                @foreach($recommendations as $rec)
                    <li class="flex justify-between items-center bg-gray-50 p-4 rounded-xl hover:bg-gray-100 transition">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $rec->lesson->title }}</p>
                            <p class="text-sm text-gray-600">{{ $rec->lesson->type }} • Level {{ $rec->lesson->level }}</p>
                        </div>
                        <a href="#" class="text-blue-600 hover:underline">Start</a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-600">No pending recommendations. Great work! 🎉</p>
        @endif
    </div>
</div>
@endsection
