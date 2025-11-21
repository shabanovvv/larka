<?php

namespace Database\Seeders;

use App\Models\CodeSubmission;
use App\Models\User;
use Illuminate\Database\Seeder;

class CodeSubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all()->pluck('id');
        $mentors = User::query()
            ->whereHas('roles', fn($q) => $q->where('name', 'mentor'))
            ->pluck('id');

        CodeSubmission::factory(20)->create([
            'user_id' => fn() => $users->random(),
            'mentor_id' => fn() => $mentors->random(),
        ]);
    }
}
