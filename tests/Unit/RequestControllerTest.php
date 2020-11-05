<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Database\Eloquent\FactoryBuilder;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\Models\User;

class RequestControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetRequesstController()
    {
        // $this->assertTrue(true);
        // $user = factory(User::class)->create();
        // $response = $this->get('/requests/general');
        // $response = $this->actingAs($user)
        //                 ->withSession(['foo' => 'bar'])
        //                 ->get('/requests/general');
        // $response->assertStatus(200);
        $response = $this->get('requests/general');
        // $user = factory(User::class)->create();
        // $this->actingAs($user);
        $response->assertStatus(302);
    }
}
