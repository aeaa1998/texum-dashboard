<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Database\Eloquent\FactoryBuilder;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\Models\Worker;

class RequestWorkersController extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetWorkersController()
    {
      
        $response = $this->get('requests/general');
        
        $response->assertStatus(302)->assertJson(["message", "successfully"]);
    }
}
