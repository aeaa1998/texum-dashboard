<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLogin() {

        $user = factory(User::class)->create();

        // HTTP TEST
        //$response = $this->actingAs($user)->assertsOk();
        //$response = $this->actingAs($user)
        //                  ->withSession(['email' => 'cschneider@hammes.org',
        //                                'password' => '123456'])
        //                    ->get('/login');

        //APPLICATION TEST
        //$this->actingAs($user)
        //      ->withSession(['email' => 'cschneider@hammes.org',
        //                     'password' => '123456'])
        //      ->visit('login')
        //      ->see();
    }
}
