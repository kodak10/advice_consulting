<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    protected $user;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testUserHasCommercialRole()
{
    // Vérifier que l'utilisateur a le rôle Commercial
    $this->assertTrue($this->user->hasRole('Commercial'));

    // Vérifier que l'utilisateur est bien authentifié
    //$this->assertAuthenticatedAs($this->user);
    // $this->user->update(['active' => true]); // Active l'utilisateur avant les tests
    $this->user->update(['status' => 'Actif']);


}
}
