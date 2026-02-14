<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Learn Igbo Part-of-Speech</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/igbo.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Igbo Part-of-Speech Learning</h2>
                <p class="card-subtitle">Master Igbo grammar with part-of-speech analysis and word meanings</p>
            </div>
            <div class="card-body">
                <!-- Input Form -->
                <form method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="sentence" class="form-control"
                               placeholder="Enter an Igbo sentence to analyze..."
                               value="{{ request('sentence', $sentence ?? '') }}" required>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Analyze
                        </button>
                    </div>
                </form>

                <!-- Results Display -->
                {{-- @if(isset($analysis) && $analysis && isset($analysis['type']) && $analysis['type'] === 'ner')
                    <div class="analysis-results">
                        <h4>Entity Recognition Analysis (Source: {{ $analysis['source'] ?? 'Unknown' }})</h4> --}}

                        <div class="sentence-display">
                            <strong>Sentence:</strong> "{{ $analysis['sentence'] ?? $sentence }}"
                        </div>

                       @if(isset($analysis['analysis']) && count($analysis['analysis']) > 0)
                        <div class="analysis-results mt-4">
                            <h4>Analysis Results:</h4>

                            <div class="sentence-display mb-3">
                                <strong>Sentence:</strong> "{{ $analysis['sentence'] ?? $sentence }}"
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
                                        @foreach($analysis['analysis'] as $item)
                                        <tr>
                                            <td><strong>{{ $item['word'] ?? '' }}</strong></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ strtoupper($item['pos'] ?? '') }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>
                                                    {{ getPOSExplanation(strtoupper($item['pos'] ?? '')) }}
                                                </small>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif


                        {{-- </div>
                        @endif --}}

                        <!-- Word Meanings Section -->
                        @if(isset($wordMeanings) && count($wordMeanings) > 0)
                        <div class="meanings-section mt-4">
                            <h4>Word Meanings</h4>
                            <div class="meanings-grid">
                                @foreach($wordMeanings as $word => $meaning)
                                <div class="meaning-card">
                                    <div class="word">{{ ucfirst($word) }}</div>
                                    <div class="meaning">{{ $meaning['meaning'] ?? '' }}</div>
                                    @if(isset($meaning['category']))
                                    <div class="category">Category: {{ $meaning['category'] }}</div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if(Auth::check() && isset($analysis['sentence']) && !empty($analysis['sentence']))
                        <div class="progress-info mt-3">
                            <i class="fas fa-chart-line text-success"></i>
                            <strong>Progress Recorded!</strong> This activity has been added to your learning history.
                        </div>
                        @endif
                    </div>

                {{-- @elseif(request()->has('sentence') || isset($sentence))
                    <div class="alert alert-warning">
                        No NER analysis found for this sentence.
                    </div>
                @endif --}}

                <!-- Sample Sentences -->
                <div class="sample-sentences mt-4">
                    <h5>Try These Sample Sentences:</h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(getSampleSentences('igbo') as $sample)
                        <a href="?sentence={{ urlencode($sample) }}" class="btn btn-outline-primary btn-sm">
                            {{ $sample }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

@php

function
getPOSExplanation($posTag) {
    $explanations = [
        'NOUN' => 'Aha (Name of a person, place, or thing)',
        'VERB' => 'Omume (Action word)',
        'ADJ'  => 'Nkowa (Describing word)',
        'ADV'  => 'Nkọwa omume (Modifies a verb)',
        'PRON' => 'Nnọchi aha (Replaces a noun)',
        'PREP' => 'Njikọ (Shows relationship)',
        'CONJ' => 'Njikọ ahịrịokwu (Connects words or clauses)',
        'DET'  => 'Nkọwapụta (Determiner)'
    ];

    return $explanations[$posTag] ?? 'Unknown part of speech';
}

    // Helper function to map common NER labels to readable text
    /**function getNERLabel($tag) {
        if (str_starts_with($tag, 'B-') || str_starts_with($tag, 'I-')) {
            $tag = substr($tag, 2);
        }
        $tag = strtoupper($tag);

        $labels = [
            'PER' => 'Person',
            'PERSON' => 'Person',
            'LOC' => 'Location',
            'LOCATION' => 'Location',
            'ORG' => 'Organization',
            'ORGANIZATION' => 'Organization',
            'O' => 'Other'
        ];
        return $labels[$tag] ?? $tag;
    }**/

    // Helper function to assign colors based on NER type
    /**function getNERColor($tag) {
        if (str_starts_with($tag, 'B-') || str_starts_with($tag, 'I-')) {
            $tag = substr($tag, 2);
        }
        $tag = strtoupper($tag);

        switch ($tag) {
            case 'PER':
            case 'PERSON':
                return 'tag-person';
            case 'LOC':
            case 'LOCATION':
                return 'tag-location';
            case 'ORG':
            case 'ORGANIZATION':
                return 'tag-organization';
            default:
                return 'tag-other';
        }
    }**/

    // Helper function for explanations
    /**function getNERExplanation($tag) {
        $explanations = [
            'Person' => 'A proper noun referring to a person.',
            'Location' => 'A proper noun referring to a geopolitical entity or place.',
            'Organization' => 'A proper noun referring to a company, group, or institution.',
            'Other' => 'No named entity detected for this token.'
        ];
        return $explanations[$tag] ?? 'General token.';
    }**/

    function getSampleSentences($language) {
        $samples = [
            'hausa' => [
                'Yaro yana karatu littafi',
                'Mace tana dafa abinci',
            ],
            'yoruba' => [
                'Ọmọkùnrin n kàwé',
                'Obìnrin ń se ounjẹ',
            ],
            'igbo' => [
                'Nwa nwoke na agu akwukwo',
                'Nwanyi na esi nri',
                'Inyinya na agba oso n\'ime ohia',
                'Umuaka na egwuri egwu n\'ama',
                'Onye nkuzi na akuziri umuaka',
                'Nnamdi ka m ga-aga Legọs',
                'Ada na-aga ụlọ akwụkwọ',
                'Chinedu bi na Enugu',
                'Ụlọ ọrụ Dangote na-arụ ọrụ'
            ]
        ];
        return $samples[$language] ?? [];
    }
@endphp
