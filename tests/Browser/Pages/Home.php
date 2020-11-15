<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class Home extends Page
{
    const TITLES = ["Paqueteria",
    "Historial",
    "Trabajadores",
    "Solicitudes de paquetes"];

    const MENU = [
        "Paqueteria" =>    
            [
                "img" => "images/texum-logo.jpeg",
                "label" => "Busqueda de la paqueteria con críterios más especificos.",
                "href" => "/packages/general",
            ],
            "Historial" =>
            [
                "img" => "images/records-image.jpg",
                "label" => "Lleva un registro de tus paquetes de donde vienen y a donde han sido reubicados.",
                "href" => "/records/general",
            ],
            "Trabajadores" =>[
                "img" => "images/workers-bg.png",
                "label" => "Manten el control de tus usuarios. Acepta y rechaza futuros usuarios.",
                "href" => "/workers",
                ],
            "Solicitudes de paquetes" =>[
                "img" => "images/move-package.jpg",
                "label" => "Reubica los paquetes que han sido ingresados en bodega.",
                "href" => "/requests/general",
                ],
            ];
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/home';
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

    // public function assertInOrder(Browser $browser)
    // {
    //     $browser->collectionAssertSee($this->url());
    // }

    public function assertInOrder(Browser $browser)
    {
        foreach (self::MENU as $key => $menu) {
            $browser->assertSee($key)
            ->assertSee($menu["label"])
            ->clickJs("#".$menu["href"])
            ->waitForLocation($menu["href"])
            ->visit("/home")
            ;
        }
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
