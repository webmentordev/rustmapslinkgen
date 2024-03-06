<?php

namespace App\Policies;

use App\Models\Map;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MapPolicy
{
    public function delete(User $user, Map $map): bool
    {
        return $user->id === $map->user_id; 
    }
}
