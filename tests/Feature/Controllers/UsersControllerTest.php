<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersControllerTest extends TestCase
{

    /**
     * @test
     */
    public function it_should_be_possible_to_view_users()
    {
        $user = factory(User::class)->create();

        $this->get('/users')
            ->assertStatus(200)
            ->assertSee($user->name);
    }

}
