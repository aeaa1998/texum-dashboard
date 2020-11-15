<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\Locker;
use App\Models\Lot;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Database\Eloquent\FactoryBuilder;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\Models\User;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use LockerSeeder;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
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
        factory(Client::class, 10)->create();
        factory(Lot::class, 10)->create();
        Artisan::call('db:seed', ['--class' => 'LockerSeeder', '--database' => 'testing']);
        Artisan::call('db:seed', ['--class' => 'RoleSeeder', '--database' => 'testing']);
        Artisan::call('db:seed', ['--class' => 'PackageStatusesSeeder', '--database' => 'testing']);
        factory(Package::class, 10)->create();
        Artisan::call('db:seed', ['--class' => 'RequestStatusTableSeeder', '--database' => 'testing']);
        $this->user->verified_at = Carbon::now();
        Schema::enableForeignKeyConstraints();
        
    }

    public function testById()
    {  
        $user = User::with('worker')->find($this->user->id);
        $response = $this->actingAs($this->user)
                        ->get("/profile/{$this->user->id}");
                        
        $response->assertStatus(200);
    }

    public function testUpdate()
    {  
        $user = User::with('worker')->find($this->user->id);
        $response = $this->actingAs($this->user)
                        ->putJson("/profiles", [
                            'email' => $this->faker->unique()->email,
                            'first_name' => $this->faker->firstName,
                            'last_name' => $this->faker->lastName,
                        ]);
                        
        $response->assertStatus(200);
    }

    public function testPassword()
    {  
        $user = User::with('worker')->find($this->user->id);
        $response = $this->actingAs($this->user)
                        ->putJson("/profile/password", [
                            'password' => "secret124",
                        ]);
                        
        $response->assertStatus(200);
        $user = User::with('worker')->find($this->user->id);
        $boold = Auth::attempt(['email' => $user->email, 'password' => 'secret124']);
        $this->assertTrue($boold);
    }

}
