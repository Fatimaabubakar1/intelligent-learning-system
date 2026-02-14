<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Learn Yoruba Part-of-Speech</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/yoruba.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Yoruba Part-of-Speech Learning</h2>
                <p class="card-subtitle">Master Yoruba grammar with part-of-speech analysis and word meanings powered by Masakhane's specialized model</p>
            </div>
            <div class="card-body">

                <form method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="sentence" class="form-control"
                               placeholder="Enter a Yoruba sentence to analyze..."
                               value="{{ $sentence }}" required>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Analyze
                        </button>
                    </div>
                </form>

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
    <td><strong>{{ $item['word'] ?? '—' }}</strong></td>
    <td>
        <span class="badge bg-info">{{ strtoupper($item['pos'] ?? 'UNKNOWN') }}</span>
    </td>
    <td>
        <small>{{ getPOSExplanation(strtoupper($item['pos'] ?? '')) }}</small>
    </td>
</tr>
@endforeach


                                </tbody>
                            </table>
                        </div>

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

                <div class="sample-sentences mt-5">
                    <h5>Try These Sample Sentences:</h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(getSampleSentences('yoruba') as $sample)
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
    function getPOSExplanation($posTag) {
        $explanations = [
            'NOUN' => 'Orúkọ (Name of person, place, thing)',
            'VERB' => 'Ìṣe (Action word)',
            'ADJ' => 'Àpèjúwe (Describing word)',
            'ADV' => 'Ìròyìn (Modifies verb)',
            'PRON' => 'Àrọ́pò orúkọ (Replaces noun)',
            'PREP' => 'Àtẹ̀yìn (Shows relationship)',
            'CONJ' => 'Àṣepọ̀ (Connecting word)',
            'DET' => 'Ìdánimọ̀ (Determiner)'
        ];
        return $explanations[$posTag] ?? 'Unknown part of speech';
    }

    function getSampleSentences($language) {
        $samples = [
            'yoruba' => [
                'Ọmọkùnrin n kàwé',
                'Obìnrin ń se ounjẹ',
                'Ẹṣin nṣáre nínú igbó',
                'Àwọn ọmọ ń ṣeré ní pápá',
                'Olùkọ́ ń kọ́ àwọn ọmọ'
            ],
        ];
        return $samples[$language] ?? [];
    }
@endphp
