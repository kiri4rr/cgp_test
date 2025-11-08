<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chunkSize = 1000;

        for ($i = 0; $i < 10000 / $chunkSize; $i++) {
            $users = [];

            for ($j = 0; $j < $chunkSize; $j++) {
                $users[] = [
                            'name' => fake()->name(),
                            'city' => fake()->city(),
                            'created_at' => now(),
                ];
            }

            DB::table('users')->insert($users);
        }
        // User::factory(10000)->create();
    }
}
