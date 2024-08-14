<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug', 50)->unique();
            $table->text('excerpt')->nullable();
            $table->mediumText('content');
            $table->string('thumbnail')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->datetimes();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog');
    }
};
