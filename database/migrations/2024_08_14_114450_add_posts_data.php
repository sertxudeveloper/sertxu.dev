<?php

use App\Models\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Post::create([

        ]);
    }

    public function down(): void
    {
        DB::table('blog')->truncate();
    }
};
