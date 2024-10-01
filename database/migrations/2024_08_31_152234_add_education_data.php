<?php

declare(strict_types=1);

use App\Models\Education;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Education::create([
            'title' => 'Technical Degree',
            'description' => 'Computer Systems Administration',
            'started_at' => '2016-09-01',
            'ended_at' => '2018-06-20',
            'location' => 'Xàtiva, Spain',
        ]);

        Education::create([
            'title' => 'Associate Degree',
            'description' => 'Network Engineering and Computer Science',
            'started_at' => '2018-09-01',
            'ended_at' => '2020-06-20',
            'location' => 'Xàtiva, Spain',
        ]);

        Education::create([
            'title' => 'Cisco CCNA',
            'description' => 'I\'ve coursed the Cisco CCNA thanks to a scholarship',
            'started_at' => '2021-05-01',
            'ended_at' => '2021-08-01',
            'location' => 'Madrid (Remote), Spain',
        ]);

        Education::create([
            'title' => 'Speciality Course',
            'description' => 'Big Data and Artificial Intelligence',
            'started_at' => '2022-10-01',
            'ended_at' => '2023-06-01',
            'location' => 'Xàtiva, Spain',
        ]);
    }

    public function down(): void
    {
        Education::truncate();
    }
};
