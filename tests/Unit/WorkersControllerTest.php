<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Database\Eloquent\FactoryBuilder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\Models\User;
use App\Models\Worker;
use Carbon\Carbon;



class RequestWorkersController extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */

     private $user= null;

     public function setUp() : void
     {
         parent::setUp();
         Schema::disableForeignKeyConstraints();
         factory(Worker::class)->create();
         $this->user = User::first();
         $this->user->verified_at = Carbon::now();
         Schema::enableForeignKeyConstraints();
         
     }

    public function testGetWorkersController()
    {
        $response = $this->actingAs($this->user)
                        ->get('/requests/general');
        $response->assertStatus(200);
    }
}
