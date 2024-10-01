<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('education', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->date('started_at');
            $table->date('ended_at')->nullable();
            $table->string('description');
            $table->string('location');
            $table->datetimes();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
