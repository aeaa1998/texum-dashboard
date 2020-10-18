<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Faker\Factory;
class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testUnsuccessfullRegisterAndLogin()
    {
        $faker = Factory::create();
        $email = $faker->email;
        $firstName = $faker->firstName();
        $lastName = $faker->lastName;
        $password = 123456;
        $this->browse(function (Browser $browser) use($email, $password, $firstName, $lastName) {
            $browser->visit('/register')
                    ->typeSlowly("firstName", $firstName, 10)
                    ->typeSlowly("lastName", $lastName, 10)
                    ->typeSlowly("email", $email, 10)
                    ->typeSlowly("password", $password, 10)
                    ->typeSlowly("passwordConfirmation", $password, 50)
                    ->click('@register-button')
                    ->pause(1500)
                    // ->assertSee('Usuario creado con exito. Por favor espera mientras te redirigimos.')
                    // ->pause(21234567)
                    ->waitForLocation('/login')
                    ->assertSee('Texsun')
                    ->typeSlowly("email", $email, 10)
                    ->typeSlowly("password", 'notCorrect', 10)
                    ->click('@login-button')
                    ->pause(3000)
                    ->assertPathIsNot('/home');
        });
    }
    public function testSuccessfullRegisterAndLogin()
    {
        $faker = Factory::create();
        $email = $faker->email;
        $firstName = $faker->firstName();
        $lastName = $faker->lastName;
        $password = 123456;
        $this->browse(function (Browser $browser) use($email, $password, $firstName, $lastName) {
            $browser->visit('/register')
                    ->typeSlowly("firstName", $firstName, 10)
                    ->typeSlowly("lastName", $lastName, 10)
                    ->typeSlowly("email", $email, 10)
                    ->typeSlowly("password", $password, 10)
                    ->typeSlowly("passwordConfirmation", $password, 50)
                    ->click('@register-button')
                    ->pause(1500)
                    // ->assertSee('Usuario creado con exito. Por favor espera mientras te redirigimos.')
                    // ->pause(21234567)
                    ->waitForLocation('/login')
                    ->assertSee('Texsun')
                    ->typeSlowly("email", $email, 10)
                    ->typeSlowly("password", $password, 10)
                    ->click('@login-button')
                    ->pause(3000)
                    ->assertPathIs('/home');
        });
    }

}
