<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_progress', function (Blueprint $table) {
            // Add the missing columns that your code is trying to use
            $table->integer('current_streak')->default(0);
            $table->integer('words_learned')->default(0);
            $table->integer('fluency_score')->default(0);
            $table->string('language_level', 10)->default('A1');
        });
    }

    public function down()
    {
        Schema::table('user_progress', function (Blueprint $table) {
            $table->dropColumn(['current_streak', 'words_learned', 'fluency_score', 'language_level']);
        });
    }
};
