<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TechnologySeeder extends Seeder
{
    const TECHNOLOGIES = [
        'php',
        'java',
        'javascript',
        'python',
        'ruby',
        'laravel',
        'symfony',
        'node.js',
        'vue.js',
        'react',
        'mysql',
        'postgresql',
    ];
    public function run(): void
    {
        foreach(self::TECHNOLOGIES as $technology) {
            Technology::factory()->create([
                'name' => $technology,
                'slug' => Str::slug($technology),
            ]);
        }
    }
}
