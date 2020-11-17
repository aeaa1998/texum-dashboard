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
use Tests\Browser\Pages\Login;
use Tests\Browser\Pages\Register;

class RegisterTest extends DuskTestCase
{
    use WithFaker, RefreshDatabase;
    private $user, $userArray;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function setUp(): void
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
        $this->user->save();
        Schema::enableForeignKeyConstraints();
	}
    public function testSuccessRegister()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Register)
                    ->successfulRegister([
                        ["selector" => "firstName", "value" => $this->faker->firstName],
                        ["selector" => "lastName", "value" => $this->faker->lastName],
                        ["selector" => "email", "value" => $this->faker->unique()->email],
                        ["selector" => "password", "value" => "123456"],
                    ["selector" => "passwordConfirmation", "value" => "123456"]
                    ]
                );
        });
    }
    public function testWrongPasswords()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Register)
                    ->typeInOrder([
                        ["selector" => "firstName", "value" => $this->faker->firstName],
                        ["selector" => "lastName", "value" => $this->faker->lastName],
                        ["selector" => "email", "value" => $this->faker->unique()->email],
                        ["selector" => "password", "value" => "123456"],
                    ["selector" => "passwordConfirmation", "value" => "1234562"]
                    ]
                )
                ->pause(300)
                ->collectionAssertSee(['Las contraseñas no coinciden']);
        });
    }
    public function testWrongPasswordsAndEmail()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Register)
                    ->typeInOrder([
                        ["selector" => "firstName", "value" => $this->faker->firstName],
                        ["selector" => "lastName", "value" => $this->faker->lastName],
                        ["selector" => "email", "value" => '$this->faker->unique()->email'],
                        ["selector" => "password", "value" => "123456"],
                    ["selector" => "passwordConfirmation", "value" => "1234562"]
                    ]
                )
                ->pause(300)
                ->collectionAssertSee(['Las contraseñas no coinciden', 'Invalid e-mail.']);
        });
    }
    public function testShortPass()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Register)
                    ->typeInOrder([
                        ["selector" => "firstName", "value" => $this->faker->firstName],
                        ["selector" => "lastName", "value" => $this->faker->lastName],
                        ["selector" => "email", "value" => '$this->faker->unique()->email'],
                        ["selector" => "password", "value" => "1"],
                    ["selector" => "passwordConfirmation", "value" => "1234562"]
                    ]
                )
                ->pause(300)
                ->collectionAssertSee(['Minimo 6 caracteres', 'Las contraseñas no coinciden']);
        });
    }
}
