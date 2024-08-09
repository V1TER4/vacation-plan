<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class VacationPlanTest extends TestCase
{
    use RefreshDatabase;

    protected $token;
    protected $vacationPlanId;

    protected function setUp(): void
    {
        parent::setUp();

        $response = $this->postJson('/api/create', [
            'name' => 'Admin',
            'email' => 'admin@admin.com.br',
            'password' => 'Jq3CAFgC14',
        ]);

        $response->assertStatus(201);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@admin.com.br',
            'password' => 'Jq3CAFgC14',
        ]);
        
        $response->assertStatus(200);
        $this->token = $response->json('token');
        
    }

    public function testListVacationPlans()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/vacation_plan');

        if ($response->status() == 400) {
            Log::error('Erro 401 no teste ListVacationPlans: ' . $response->getContent());
        } else {
            $response->assertStatus(200);
            return $response->json('data');
        }
    }

    public function testCreateVacationPlan()
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->postJson('/api/vacation_plan', [
                             'title' => 'Viagem para a praia',
                             'description' => 'Viagem para Acapulco',
                             'location' => 'Acapulco, Mexico',
                             'date' => '2024-11-12',
                             'participants' => [
                                 ['name' => 'Maria'],
                                 ['name' => 'João'],
                                 ['name' => 'Paulo'],
                                 ['name' => 'Rafael'],
                                 ['name' => 'Vinicius']
                             ]
                         ]);

        if ($response->status() == 400) {
            Log::error('Erro 401 no teste CreateVacationPlan: ' . $response->getContent());
        } else {
            $response->assertStatus(200);
            return $response->json('data');
        }
    }

    public function testShowVacationPlans()
    {
        $this->testCreateVacationPlan();
        $vacation = $this->testListVacationPlans();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/vacation_plan/'. $vacation[0]['id']);

        if ($response->status() == 400) {
            Log::error('Erro 401 no teste ListVacationPlans: ' . $response->getContent());
        } else {
            $response->assertStatus(200);
        }
    }

    public function testUpdateVacationPlan()
    {
        $vacation = $this->testCreateVacationPlan();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->putJson('/api/vacation_plan/' . $vacation['id'], [
                             'title' => 'Teste 2',
                             'description' => 'Teste 1',
                             'location' => 'Casa',
                             'date' => '2024-12-11',
                             'participants' => [
                                 ['name' => 'Teresa'],
                                 ['name' => 'Mateus'],
                                 ['name' => 'Katia']
                             ]
                         ]);
        
        if ($response->status() == 400 || $response->status() == 401) {
            Log::error('Erro 401 no teste UpdateVacationPlan: ' . $response->getContent());
        } else {
            $response->assertStatus(200);
            return $response->json('baseResponse.data');
        }
    }

    public function testDeleteVacationPlan()
    {
        $vacation = $this->testCreateVacationPlan();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->deleteJson('/api/vacation_plan/'. $vacation['id']);

        if ($response->status() == 401) {
            Log::error('Erro 401 no teste DeleteVacationPlan: ' . $response->getContent());
        } else {
            $response->assertStatus(200);
        }
    }

    public function testExportVacationPlan()
    {
        $vacation = $this->testCreateVacationPlan();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->postJson('/api/vacation_plan/export', [
                             'date' => $vacation['date']
                         ]);

        if ($response->status() == 400 || $response->status() == 401) {
            Log::error('Erro 400 no teste ExportVacationPlan: ' . $response->getContent());
        } else {
            $response->assertStatus(200);
        }
    }
}
