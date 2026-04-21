<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('policies')) {
            Schema::create('policies', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->longText('content');
                $table->longText('embedding')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
