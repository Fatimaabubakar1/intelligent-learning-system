<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_progress', function (Blueprint $table) {
            $table->string('module_type')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('user_progress', function (Blueprint $table) {
            $table->string('module_type')->nullable(false)->change();
        });
    }
};
