<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function testRegister() {
        $response = $this->postJson('api/register',['first_name' => 'Pedro',
                                                    'last_name' => 'Rodriguez',
                                                    'email' => 'example@ex.com',
                                                    'password' => '123456']);
        $response->assertStatus(200)->assertJson(["message", "successfully created"]);        
    }
}
