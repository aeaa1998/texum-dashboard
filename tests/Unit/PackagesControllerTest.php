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
use Faker\Generator as Faker;
use LockerSeeder;

class PackagesControllerTest extends TestCase
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

    public function testGetPackagesController()
    {  
        $response = $this->actingAs($this->user)
                        ->get('/packages/general');
        $response->assertStatus(200);
    }

    public function testGetPackagesControllerWithStress()
    {  
        
        factory(Package::class, 1000)->create();
        $starttime = microtime(true);
        $response = $this->actingAs($this->user)
                        ->get('/packages/general');
        $response->assertStatus(200);
        $endtime = microtime(true);
        $timediff = $endtime - $starttime;
        $this->assertTrue($timediff <= 5);
    }

    public function testGetPackagesControllerWithStressAndParams()
    {  
        
        factory(Package::class, 1000)->create();
        $starttime = microtime(true);
        $response = $this->actingAs($this->user)
                        ->get('/packages/general?query=here&lot_id=1');
        $response->assertStatus(200);
        $endtime = microtime(true);
        $timediff = $endtime - $starttime;
        $this->assertTrue($timediff <= 5);
    }

    public function testPostPackage()
    {  
        $locker  = Locker::first();
        $lot  = Lot::first();
        $response = $this
        ->actingAs($this->user)
        ->postJson('/packages', 
        [
            'bar_code' => $this->faker->uuid,
            'column'   => $locker->column,
            'letter'   => $locker->letter,
            'row'   => $locker->row,
            'lot_id'  => $lot->id,
        ]);
        
        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => "Creado exitosamente",
            ]);
    }

    public function testPutPackage()
    {  
        $packageId = Package::first()->id;
        $locker  = Locker::first();
        $lot  = Lot::first();
        $response = $this
        ->actingAs($this->user)
        ->putJson("/packages/$packageId", 
        [
            'bar_code' => $this->faker->uuid,
            'lot_id'  => $lot->id,
        ]);

        $response
            ->assertStatus(200);
    }

}
