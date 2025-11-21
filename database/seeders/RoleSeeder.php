<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach(['admin', 'mentor', 'student'] as $role) {
            Role::factory()->create([
                'name' => $role,
            ]);
        }
    }
}
