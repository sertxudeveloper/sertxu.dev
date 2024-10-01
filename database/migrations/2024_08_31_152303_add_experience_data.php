<?php

declare(strict_types=1);

use App\Models\Experience;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Experience::create([
            'title' => 'Scuola Centrale Formazione',
            'description' => 'I was an intern at an Erasmus+ program developing several websites for different organizations',
            'started_at' => '2018-05-01',
            'ended_at' => '2018-06-10',
            'location' => 'Bologna, Italy',
        ]);

        Experience::create([
            'title' => 'IMASD',
            'description' => "I was an intern during few months doing:\n* Write documentation about our products\n* Develop new solutions based on client requirements",
            'started_at' => '2018-07-01',
            'ended_at' => '2019-01-01',
            'location' => 'Ontinyent, Spain',
        ]);

        Experience::create([
            'title' => 'Unelink',
            'description' => "I was responsible of multiple tasks:\n* Provide L1 and L2 support for our products\n* Write documentation about solved issues\n* Develop and maintain in-house web applications\n* Try new technologies to offer new products",
            'started_at' => '2020-08-15',
            'ended_at' => '2021-08-03',
            'location' => 'Valencia, Spain',
        ]);

        Experience::create([
            'title' => 'Transpack Group',
            'description' => "I'm responsible of multiple tasks:\n* Provide L1, L2 and L3 support for third party apps\n* Make custom developments at Sage ERP\n* Develop and maintain in-house web applications\n* Buy, install and configure new hardware devices",
            'started_at' => '2022-01-02',
            'ended_at' => null,
            'location' => 'Xàtiva, Spain',
        ]);
    }

    public function down(): void
    {
        Experience::truncate();
    }
};
