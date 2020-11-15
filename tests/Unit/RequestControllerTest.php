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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use LockerSeeder;

class RequestControllerTest extends TestCase
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

    public function testGetRequestController()
    {  
        $response = $this->actingAs($this->user)
                        ->get('/requests/general');
        $response->assertStatus(200);
    }

    public function testGetRequestControllerWithStress()
    {  
        
        factory(Package::class, 1000)->create();
        $starttime = microtime(true);
        $response = $this->actingAs($this->user)
                        ->get('/requests/general');
        $response->assertStatus(200);
        $endtime = microtime(true);
        $timediff = $endtime - $starttime;
        $this->assertTrue($timediff <= 5);
    }

    public function testGetRequestControllerWithStressAndParams()
    {  
        
        factory(Package::class, 1000)->create();
        $starttime = microtime(true);
        $response = $this->actingAs($this->user)
                        ->get('/requests/general?query=here&lot_id=1');
        $response->assertStatus(200);
        $endtime = microtime(true);
        $timediff = $endtime - $starttime;
        $this->assertTrue($timediff <= 5);
    }

    public function testPostRequest()
    {  
        $package = Package::with('lastRecord.newLocker')->first();
        
        $oldLocker = $package->lastRecord->newLocker;
        $locker  = Locker::where('id', '!=', $oldLocker->id)->first();
        ;
        $response = $this
        ->actingAs($this->user)
        ->postJson('/requests', 
        [
            'package_id'   => $package->id,
            'old_locker_row'  => $oldLocker->row,
            'old_locker_column'  => $oldLocker->column,
            'old_locker_letter'  => $oldLocker->letter,
            'new_locker_row'  => $locker->row,
            'new_locker_column'  => $locker->column,
            'new_locker_letter'  => $locker->letter,
        ]);
        // dd($response);
        $response
            ->assertStatus(201);
    }
}
