<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserImage;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Str;

class UserImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $chunkSize = 10000;
        $userIdsCount = User::count();
        for ($i = 0; $i < 100000 / $chunkSize; $i++) {
            $userImages = [];
            for ($j = 0; $j < $chunkSize; $j++) {
                $userImages[] = [
                    'user_id' => random_int(1, $userIdsCount),
                    'image' => 'https://via.placeholder.com/640x480.png/'.Str::random(length: 8).'?text=' . Str::random(length: 16)
                ];
            }
            DB::table('user_images')->insert($userImages);
        }

        // UserImage::factory()->count(100000)->create();
    }
}