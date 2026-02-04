<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('language', ['hausa', 'yoruba', 'igbo']);
            $table->enum('module_type', ['hausa_pos', 'yoruba_pos', 'igbo_ner']);
            $table->json('activity_data');
            $table->decimal('score', 5, 2)->nullable();
            $table->timestamp('completed_at');
            $table->timestamps();

            $table->index(['user_id', 'language']);
            $table->index('completed_at');
    });
}
    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
