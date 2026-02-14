<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Learn Hausa Part-of-Speech</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/hausa.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Hausa Part-of-Speech Learning</h2>
                <p class="card-subtitle">Analyze Hausa sentences with AI-powered POS tagging and word meanings</p>
            </div>
            <div class="card-body">

                <form method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="sentence" class="form-control"
                               placeholder="Enter a Hausa sentence to analyze..."
                               value="{{ $sentence }}" required>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Analyze
                        </button>
                    </div>
                </form>

                <!-- Results -->
                @if($sentence && !empty($analysis))
                    <div class="analysis-results mt-4">
                        <h4>Analysis Results:</h4>
                        <div class="sentence-display mb-3">
                            <strong>Sentence:</strong> "{{ $analysis['sentence'] }}"
                        </div>

                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Word</th>
                                        <th>Part of Speech</th>
                                        <th>Explanation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analysis['analysis'] ?? [] as $item)
                                        <tr>
                                            <td><strong>{{ $item['word'] }}</strong></td>
                                            <td>
                                                <span class="badge bg-info">{{ strtoupper($item['pos']) }}</span>
                                            </td>
                                            <td>
                                                <small>{{ getPOSExplanation(strtoupper($item['pos'])) }}</small>
                                            </td>
                                        </tr>
                                        @endforeach

                                </tbody>
                            </table>
                        </div>

                        <!-- Word Meanings Section -->
                        @if(isset($wordMeanings) && count($wordMeanings) > 0)
                        <div class="meanings-section">
                            <h4>Word Meanings</h4>
                            <div class="meanings-grid">
                                @foreach($wordMeanings as $word => $meaning)
                                <div class="meaning-card">
                                    <div class="word">{{ ucfirst($word) }}</div>
                                    <div class="meaning">{{ $meaning['meaning'] }}</div>
                                    <div class="category">Category: {{ $meaning['category'] }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if(Auth::check())
                        <div class="progress-info mt-3 p-3 rounded">
                            <i class="fas fa-chart-line text-success"></i>
                            <strong>Progress Recorded!</strong> This activity has been added to your learning history.
                        </div>
                        @endif
                    </div>
                @elseif($sentence)
                    <div class="alert alert-warning">
                        No analysis available for this sentence. Try a different one.
                    </div>
                @endif

                <!-- Sample sentences completely removed -->
            </div>
        </div>
    </div>
</body>
</html>

@php
    function getPOSExplanation($posTag) {
        $explanations = [
            'NOUN' => 'Sunna (Name of person, place, thing)',
            'VERB' => 'Fi\'ili (Action word)',
            'ADJ' => 'Siffa (Describing word)',
            'ADV' => 'Magana (Modifies verb)',
            'PRON' => 'Madadin suna (Replaces noun)',
            'PREP' => 'Gaba da suna (Shows relationship)',
            'CONJ' => 'Mahaɗi (Connecting word)',
            'DET' => 'Mai ƙayyadad da suna (Determiner)'
        ];
        return $explanations[$posTag] ?? 'Unknown part of speech';
    }

    // The getSampleSentences function is kept but not used anywhere
    function getSampleSentences($language) {
        $samples = [
            'hausa' => [
                'Yaro yana karatu littafi',
                'Mace tana dafa abinci',
                'Doki yana gudu cikin daji',
                'Yara suna wasa a filin',
                'Malamin yana koyar da yara'
            ],

        ];
        return $samples[$language] ?? [];
    }
@endphp
