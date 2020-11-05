<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class Login extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/login';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }

    public function successfulLogin(Browser $browser, $user, $logout = false)
    {
        $browser->typeInOrder($user)
            ->pause(1000)
            ->click('@login')
            ->waitForLocation('/home')
            ->collectionAssertSee(['Paqueteria']);
        if ($logout == true) {
            $browser->click('@account-btn')
            
            ->clickJs("#logout")
                    ->waitForLocation('/login')
                ->assertSee('Ingresa tus credenciales');
        }
    }

    public function unsuccessfullLogin(Browser $browser)
    {
        $browser->typeInOrder([["selector" => "email", "value" => "lol@lolito.com"], ["selector" => "password", "value" => "mipassssincorrecta"]])
        ->pause(1000)
            ->click('@login')
            ->waitFor('#invalid-alert')
            ->assertSee('Credenciales inválidas');
    }
}
