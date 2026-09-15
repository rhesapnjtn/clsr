<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => env('ADMIN_EMAIL', 'admin@clsr.local')],
            [
                'name' => 'Admin Penyelenggara CLSR',
                'email' => env('ADMIN_EMAIL', 'admin@clsr.local'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'admin1234')),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->demoBatch();
    }

    private function demoBatch(): void
    {
        $exists = DB::table('batches')->where('code', 'CLSR-DEMO')->exists();

        if ($exists) {
            return;
        }

        DB::table('batches')->insert([
            'code' => 'CLSR-DEMO',
            'title' => 'Pelatihan CLSR - Batch Demo',
            'training_date' => now()->addDays(14)->toDateString(),
            'start_time' => '08:00',
            'end_time' => '16:00',
            'location' => 'Training Room, Building Utama',
            'max_participants' => 30,
            'status' => 'open',
            'is_locked' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
