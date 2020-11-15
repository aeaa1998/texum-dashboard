<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class Register extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/register';
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
    public function successfulRegister(Browser $browser, $user, $logout = false)
    {
        $browser->typeInOrder($user)
            ->pause(1000)
            ->click('@register-button')
            ->waitForLocation('/login')
            ->collectionAssertSee(['Texsun S.A']);
    }

    public function unsuccessfulRegister(Browser $browser, $user, $logout = false)
    {
        $browser->typeInOrder($user)
            ->pause(1000)
            
            ->waitForLocation('/login')
            ->collectionAssertSee(['Texsun S.A']);
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
}
