<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('language_data')->insert([
            'language' => 'hausa',
            'original_text' => 'Sannu',
            'translation' => 'Hello',
            'category' => 'Greeting',
            'created_at' => now(),
            'updated_at' => now(),
]);

    }
}
