<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ApplicantLoginTest extends DuskTestCase
{
	use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Laravel');
        });
    }
	
	public function testApplicantLogin()
    {
        $user = factory(User::class)->create([
            'email' => 'taylor@laravel.com',
			'role' => 'applicant',
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/applicants/login')
                    ->type('email', $user->email)
                    ->type('password', 'secret')
                    ->press('Login')
                    ->assertPathIs('/applicants');
        });
    }
}
