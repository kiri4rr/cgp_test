<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    /**
     * Find or create user by attributes
     */
    public function firstOrCreateUser(array $attributes): User
    {
        return User::firstOrCreate($attributes);
    }

    /**
     * Get users ordered by images count
     */
    public function getUsersOrderedByImagesCount(): Collection
    {
        return User::query()
            ->withCount('user_images')
            ->orderByDesc('user_images_count')
            ->get();
    }
}
