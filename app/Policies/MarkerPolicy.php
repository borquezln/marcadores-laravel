<?php

namespace App\Policies;

use App\Models\Marker;
use App\Models\User;

class MarkerPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isEditor();
    }

    public function view(User $user, Marker $marker): bool
    {
        return $user->isEditor() || $user->isViewer();
    }

    public function create(User $user): bool
    {
        return $user->isEditor();
    }

    public function update(User $user, Marker $marker): bool
    {
        return $this->owns($user, $marker);
    }

    public function delete(User $user, Marker $marker): bool
    {
        return $this->owns($user, $marker);
    }

    private function owns(User $user, Marker $marker): bool
    {
        return $marker->user_id === $user->id;
    }
}
