<?php

namespace Database\Seeders;

use App\Models\MentorProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class MentorProfileSeeder extends Seeder
{
    public function run(): void
    {
        $mentors = User::query()
            ->whereHas('roles', fn($q) => $q->where('name', 'mentor'))
            ->get();

        foreach($mentors as $mentor) {
            MentorProfile::factory()->create([
                'user_id' => $mentor->id,
            ]);
        }
    }
}
