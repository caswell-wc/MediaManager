<?php

namespace App\Policies;

use App\File;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view a file.
     *
     * @param User $user
     * @param File $file
     * @return bool
     */
    public function view(User $user, File $file): bool
    {
        return $file->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete a file
     *
     * @param User $user
     * @param File $file
     * @return bool
     */
    public function delete(User $user, File $file): bool
    {
        return $file->user_id == $user->id;
    }
}
