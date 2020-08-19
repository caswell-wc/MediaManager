<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Sign in the given user or create a new user and sign that user in
     *
     * @param null|User $user
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    public function signIn($user = null)
    {
        if (empty($user)) {
            $user = factory(User::class)->create();
        }
        $this->actingAs($user);

        return $user;
    }
}
