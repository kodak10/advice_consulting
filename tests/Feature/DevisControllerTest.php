<?php

namespace Tests\Feature;

use App\Models\Banque;
use App\Models\Client;
use App\Models\Designation;
use App\Models\Devis;
use App\Models\DevisDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DevisControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

   

    protected function setUp(): void
    {
        parent::setUp();
    
        // Créer les rôles
        Role::create(['name' => 'Administrateur']);
        Role::create(['name' => 'Commercial']);
        Role::create(['name' => 'Comptable']);
        Role::create(['name' => 'DAF']);
        Role::create(['name' => 'DG']);
    
        // Créer les permissions nécessaires
        Permission::create(['name' => 'create devis']);
        Permission::create(['name' => 'edit devis']);
        Permission::create(['name' => 'delete devis']);
        Permission::create(['name' => 'view devis']);
        Permission::create(['name' => 'approve devis']);
        Permission::create(['name' => 'refuse devis']);
    
        // Assigner les permissions aux rôles
        $adminRole = Role::findByName('Administrateur');
        $adminRole->givePermissionTo(['create devis', 'edit devis', 'delete devis', 'view devis', 'approve devis', 'refuse devis']);
    
        $commercialRole = Role::findByName('Commercial');
        $commercialRole->givePermissionTo(['create devis', 'edit devis', 'view devis']);
    
        $comptableRole = Role::findByName('Comptable');
        $comptableRole->givePermissionTo(['view devis', 'approve devis', 'refuse devis']);
    
        $dafRole = Role::findByName('DAF');
        $dafRole->givePermissionTo(['view devis', 'approve devis', 'refuse devis']);
    
        $dgRole = Role::findByName('DG');
        $dgRole->givePermissionTo(['view devis', 'approve devis', 'refuse devis']);
    
        // Créer un utilisateur avec un rôle spécifique pour les tests
        $this->user = User::factory()->create();
        $this->user->assignRole('Commercial'); // Assigner le rôle Commercial
    
        // Authentifier l'utilisateur pour les tests
        $this->actingAs($this->user);
    }

    


    // Test pour la méthode index
    public function testIndex()
    {
        $response = $this->get(route('dashboard.devis.index'));
        $response->assertStatus(200);
        $response->assertViewHas(['devis', 'mes_devis', 'devisAlls']);
    }

    // Test pour la méthode create
    public function testCreate()
    {
        $response = $this->get(route('dashboard.devis.create'));
        $response->assertStatus(200);
        $response->assertViewHas(['clients', 'designations', 'banques', 'devises', 'rates']);
    }

    // Test pour la méthode generateNumProforma
    public function testGenerateNumProforma()
    {
        $controller = new \App\Http\Controllers\Administration\DevisController();
        $numProforma = $controller->generateNumProforma();
        $this->assertStringStartsWith('ADC', $numProforma);
    }

    // Test pour la méthode approuve
    public function testApprouve()
    {
        $devis = Devis::factory()->create(['status' => 'En Attente de validation']);
        $response = $this->post(route('dashboard.devis.approuve', $devis->id));
        $response->assertRedirect(route('dashboard.devis.index'));
        $this->assertEquals('Facturé', $devis->fresh()->status);
    }

    // Test pour la méthode refuse
    public function testRefuse()
    {
        $devis = Devis::factory()->create(['status' => 'Facturé']);
        $response = $this->post(route('dashboard.devis.refuse', $devis->id), [
            'message' => 'Ce devis est refusé pour des raisons spécifiques.'
        ]);
        $response->assertRedirect(route('dashboard.devis.index'));
        $this->assertEquals('Réfusé', $devis->fresh()->status);
    }

    // Test pour la méthode recap
    public function testRecap()
    {
        $client = Client::factory()->create();
        $banque = Banque::factory()->create();
        $designation = Designation::factory()->create();

        $response = $this->post(route('dashboard.devis.recap'), [
            'client_id' => $client->id,
            'banque_id' => $banque->id,
            'date_emission' => now()->format('Y-m-d'),
            'date_echeance' => now()->addDays(30)->format('Y-m-d'),
            'commande' => 'Commande test',
            'livraison' => 'Livraison test',
            'validite' => 'Validité test',
            'delai' => '30 jours',
            'total-ht' => 1000,
            'tva' => 20,
            'total-ttc' => 1200,
            'acompte' => 600,
            'solde' => 600,
            'designations' => [
                [
                    'id' => $designation->id,
                    'description' => 'Description test',
                    'quantity' => 1,
                    'price' => 1000,
                    'discount' => 0,
                    'total' => 1000
                ]
            ]
        ]);

        $response->assertStatus(200);
        $response->assertViewHas(['client', 'validated', 'banque', 'designations']);
    }

    // Test pour la méthode store
    public function testStore()
    {
        Storage::fake('public');
        Mail::fake();
        Notification::fake();

        $client = Client::factory()->create();
        $banque = Banque::factory()->create();
        $designation = Designation::factory()->create();

        $response = $this->post(route('dashboard.devis.store'), [
            'client_id' => $client->id,
            'banque_id' => $banque->id,
            'date_emission' => now()->format('Y-m-d'),
            'date_echeance' => now()->addDays(30)->format('Y-m-d'),
            'commande' => 'Commande test',
            'livraison' => 'Livraison test',
            'validite' => 'Validité test',
            'delai' => '30 jours',
            'total-ht' => 1000,
            'tva' => 20,
            'total-ttc' => 1200,
            'acompte' => 600,
            'solde' => 600,
            'designations' => [
                [
                    'id' => $designation->id,
                    'description' => 'Description test',
                    'quantity' => 1,
                    'price' => 1000,
                    'discount' => 0,
                    'total' => 1000
                ]
            ],
            'devise' => 'USD',
            'taux' => 1.2,
            'texte' => 'Texte test'
        ]);

        $response->assertRedirect(route('dashboard.devis.index'));
        $this->assertDatabaseHas('devis', ['commande' => 'Commande test']);
    }

    // Test pour la méthode edit
    public function testEdit()
    {
        $devis = Devis::factory()->create();
        $response = $this->get(route('dashboard.devis.edit', $devis->id));
        $response->assertStatus(200);
        $response->assertViewHas(['devis', 'clients', 'banques', 'designations', 'devises', 'rates']);
    }

    // Test pour la méthode recapUpdate
    public function testRecapUpdate()
    {
        $devis = Devis::factory()->create();
        $client = Client::factory()->create();
        $banque = Banque::factory()->create();
        $designation = Designation::factory()->create();

        $response = $this->post(route('dashboard.devis.recapUpdate', $devis->id), [
            'client_id' => $client->id,
            'banque_id' => $banque->id,
            'date_emission' => now()->format('Y-m-d'),
            'date_echeance' => now()->addDays(30)->format('Y-m-d'),
            'commande' => 'Commande test',
            'livraison' => 'Livraison test',
            'validite' => 'Validité test',
            'delai' => '30 jours',
            'total-ht' => 1000,
            'tva' => 20,
            'total-ttc' => 1200,
            'acompte' => 600,
            'solde' => 600,
            'designations' => [
                [
                    'id' => $designation->id,
                    'description' => 'Description test',
                    'quantity' => 1,
                    'price' => 1000,
                    'discount' => 0,
                    'total' => 1000
                ]
            ],
            'devise' => 'USD',
            'taux' => 1.2,
            'texte' => 'Texte test'
        ]);

        $response->assertStatus(200);
        $response->assertViewHas(['client', 'validated', 'banque', 'designations', 'devis']);
    }

    // Test pour la méthode storeRecap
    public function testStoreRecap()
    {
        Storage::fake('public');
        $devis = Devis::factory()->create();
        $client = Client::factory()->create();
        $banque = Banque::factory()->create();
        $designation = Designation::factory()->create();

        $response = $this->post(route('dashboard.devis.storeRecap', $devis->id), [
            'client_id' => $client->id,
            'banque_id' => $banque->id,
            'date_emission' => now()->format('Y-m-d'),
            'date_echeance' => now()->addDays(30)->format('Y-m-d'),
            'commande' => 'Commande test',
            'livraison' => 'Livraison test',
            'validite' => 'Validité test',
            'delai' => '30 jours',
            'total-ht' => 1000,
            'tva' => 20,
            'total-ttc' => 1200,
            'acompte' => 600,
            'solde' => 600,
            'designations' => [
                [
                    'id' => $designation->id,
                    'description' => 'Description test',
                    'quantity' => 1,
                    'price' => 1000,
                    'discount' => 0,
                    'total' => 1000
                ]
            ],
            'devise' => 'USD',
            'taux' => 1.2,
            'texte' => 'Texte test'
        ]);

        $response->assertRedirect(route('dashboard.devis.index'));
        $this->assertDatabaseHas('devis', ['commande' => 'Commande test']);
    }

    // Test pour la méthode destroy
    public function testDestroy()
    {
        $devis = Devis::factory()->create(['status' => 'En Attente de validation']);
        $response = $this->delete(route('dashboard.devis.destroy', $devis->id));
        $response->assertRedirect(route('dashboard.devis.index'));
        $this->assertDatabaseMissing('devis', ['id' => $devis->id]);
    }

    // Test pour la méthode download
    public function testDownload()
    {
        Storage::fake('public');
        $devis = Devis::factory()->create(['pdf_path' => 'pdf/devis/test.pdf']);
        Storage::disk('public')->put($devis->pdf_path, 'dummy content');

        $response = $this->get(route('dashboard.devis.download', $devis->id));
        $response->assertDownload();
    }

    // Test pour la méthode exportCsv
    public function testExportCsv()
    {
        $devis = Devis::factory()->create();
        $response = $this->get(route('dashboard.devis.exportCsv'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
    }
}