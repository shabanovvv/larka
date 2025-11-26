<?php

namespace Database\Seeders;

use App\Models\MentorProfile;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Database\Seeder;

class MentorProfileSeeder extends Seeder
{
    public function run(): void
    {
        $mentors = User::query()
            ->whereHas('roles', fn($q) => $q->where('name', 'mentor'))
            ->get();
        $technologyIds = Technology::query()->pluck('id');

        foreach($mentors as $mentor) {
            $profile = MentorProfile::factory()->create([
                'user_id' => $mentor->id,
            ]);

            /** @var MentorProfile $profile */
            $profile->technologies()->attach(
                $technologyIds->random(rand(1, 3))
            );
        }
    }
}
