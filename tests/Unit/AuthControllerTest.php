<?php

namespace Tests\Unit;

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

class AuthControllerTest extends TestCase
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
        parent::setUp();
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

    public function testLoginPre()
    {  
        $response = $this
                        ->postJson('/register', [
                            'email'      => $this->faker->unique()->email,
                            'password'   => "123456",
                            'first_name' => $this->faker->firstName,
                            'last_name'  => $this->faker->lastName,
                        ]);
                        
        $response->assertStatus(200)
        ->assertJson([
            'message' => "successfully created",
        ]);
    }

    public function testLoginNotVerified()
    {  
        $email = $this->faker->unique()->email;
        $response = $this
                        ->postJson('/register', [
                            'email'      => $email,
                            'password'   => "123456",
                            'first_name' => $this->faker->firstName,
                            'last_name'  => $this->faker->lastName,
                        ]);
                        
        $response->assertStatus(200)
        ->assertJson([
            'message' => "successfully created",
        ]);
        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $response = $this
        ->postJson('/login', [
            'email'      => $email,
            'password'   => "123456",
        ]);
        
    $response->assertStatus(411);
    }

    public function testLogin()
    {  
        $email = $this->faker->unique()->email;
        $response = $this
                        ->postJson('/register', [
                            'email'      => $email,
                            'password'   => "123456",
                            'first_name' => $this->faker->firstName,
                            'last_name'  => $this->faker->lastName,
                        ]);
                        
        $response->assertStatus(200)
        ->assertJson([
            'message' => "successfully created",
        ]);
        $user = User::where('email', $email)->first();
        $user->verified_at = Carbon::now();
        $user->save();
        $this->assertNotNull($user);
        $response = $this
        ->postJson('/login', [
            'email'      => $email,
            'password'   => "123456",
        ]);
        
    $response->assertStatus(200);
    }

}
