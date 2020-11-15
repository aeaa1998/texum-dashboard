<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Database\Eloquent\FactoryBuilder;
use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Http\Request;
use Tests\TestCase;

use Illuminate\Support\Facades\Schema;
use App\Models\Worker;
use Carbon\Carbon;


class RequestWorkersControllerTest extends TestCase
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
        parent::setup();
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
