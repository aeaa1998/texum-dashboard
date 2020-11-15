<?php

namespace Tests\Browser;
use App\Models\Client;
use App\Models\Lot;
use App\Models\Package;
use App\Models\User;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Tests\Browser\Pages\Home;
use Tests\Browser\Pages\Login;
class HomeTest extends DuskTestCase
{
    use WithFaker, RefreshDatabase;
    private $user, $userArray;
    /**
     * A Dusk test example.
     *
     * @return void
     */
	public function setUp(): void{
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
        $this->user->save();
        Schema::enableForeignKeyConstraints();
	}

    /** @test */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser
            ->visit(new Login)
            ->successfulLogin([["selector" => "email", "value" => $this->user->email],["selector" => "password", "value" => "123456"]])
            ->visit(new Home)
                    ->assertInOrder();
        });
    }
}
