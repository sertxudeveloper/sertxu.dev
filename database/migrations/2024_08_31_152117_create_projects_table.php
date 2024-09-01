<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug', 50)->unique();
            $table->tinyText('excerpt')->nullable();
            $table->longText('text')->nullable();
            $table->string('website')->nullable();
            $table->boolean('is_published')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->datetimes();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
