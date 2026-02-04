<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DatasetService
{
    private $huggingFaceToken;

    public function __construct()
    {
        $this->huggingFaceToken = env('HUGGING_FACE_TOKEN');
    }

    // Hausa Dataset
    public function getHausaDataset($query = '', $limit = 10)
    {
        return Cache::remember("hausa_data_{$query}", 3600, function () use ($query, $limit) {
            try {
                $response = Http::withToken($this->huggingFaceToken)
                    ->post('https://api-inference.huggingface.co/models/dslim/bert-base-NER', [
                        'inputs' => $query ?: 'Hausa language text',
                    ]);

                return $response->successful() ? $response->json() : $this->getHausaSampleData();
            } catch (\Exception $e) {
                return $this->getHausaSampleData();
            }
        });
    }

    // Igbo Dataset
    public function getIgboDataset($query = '', $limit = 10)
    {
        return Cache::remember("igbo_data_{$query}", 3600, function () use ($query, $limit) {
            try {
                $response = Http::withToken($this->huggingFaceToken)
                    ->post('https://api-inference.huggingface.co/models/google/bert-base-multilingual-cased', [
                        'inputs' => $query ?: 'Igbo language text',
                    ]);

                return $response->successful() ? $response->json() : $this->getIgboSampleData();
            } catch (\Exception $e) {
                return $this->getIgboSampleData();
            }
        });
    }

    // Yoruba Dataset
    public function getYorubaDataset($query = '', $limit = 10)
    {
        return Cache::remember("yoruba_data_{$query}", 3600, function () use ($query, $limit) {
            try {
                $response = Http::withToken($this->huggingFaceToken)
                    ->post('https://api-inference.huggingface.co/models/Davlan/bert-base-multilingual-cased-ner-hrl', [
                        'inputs' => $query ?: 'Yoruba language text',
                    ]);

                return $response->successful() ? $response->json() : $this->getYorubaSampleData();
            } catch (\Exception $e) {
                return $this->getYorubaSampleData();
            }
        });
    }

    // Sample data fallbacks
    private function getHausaSampleData()
    {
        return [
            ['text' => 'Ina son karatu Hausa', 'translation' => 'I like learning Hausa'],
            ['text' => 'Yaya ake cewa ...', 'translation' => 'How do you say ...'],
            ['text' => 'Hausa yare ne mai kyau', 'translation' => 'Hausa is a beautiful language'],
        ];
    }

    private function getIgboSampleData()
    {
        return [
            ['text' => 'Achọrọ m ịmụ asụsụ Igbo', 'translation' => 'I want to learn Igbo language'],
            ['text' => 'Kedu ka esi ekwu ...', 'translation' => 'How do you say ...'],
            ['text' => 'Asụsụ Igbo dị mma', 'translation' => 'Igbo language is good'],
        ];
    }

    private function getYorubaSampleData()
    {
        return [
            ['text' => 'Mo feran lati ko ede Yoruba', 'translation' => 'I like to learn Yoruba language'],
            ['text' => 'Bawo ni a se n so ...', 'translation' => 'How do you say ...'],
            ['text' => 'Ede Yoruba dara', 'translation' => 'Yoruba language is good'],
        ];
    }
}
