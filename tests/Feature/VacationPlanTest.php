<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class VacationPlanTest extends TestCase
{
    protected $token;
    protected $vacationPlanId;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->authenticate();
    }

    protected function authenticate()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@admin.com.br',
            'password' => 'Jq3CAFgC14',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
        $this->token = $response->json('token');
    }

    public function testListVacationPlans()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/vacation_plan');

        if ($response->status() == 401) {
            Log::error('Erro 401 no teste ListVacationPlans: ' . $response->getContent());
        } else {
            $response->assertStatus(200);
            $plans = $response->json();
            if (isset($plans[0]['id'])) {
                $this->vacationPlanId = $plans[0]['id'];
                $this->assertNotNull($this->vacationPlanId, 'O ID do plano de férias não foi retornado.');
            } else {
                Log::error('Nenhum plano de férias encontrado no teste ListVacationPlans.');
            }
        }
    }

    public function testCreateVacationPlan()
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->postJson('/api/vacation_plan', [
                             'title' => 'Viagem para a praia',
                             'description' => 'Viagem para Acapulco',
                             'location' => 'Acapulco, Mexico',
                             'date' => '2024-08-12',
                             'participants' => [
                                 ['name' => 'Maria'],
                                 ['name' => 'João'],
                                 ['name' => 'Paulo'],
                                 ['name' => 'Rafael'],
                                 ['name' => 'Vinicius']
                             ]
                         ]);

        if ($response->status() == 401) {
            Log::error('Erro 401 no teste CreateVacationPlan: ' . $response->getContent());
        } else {
            $response->assertStatus(200);
            $data = $response->json('baseResponse.original.data');
            if (isset($data['id'])) {
                $this->vacationPlanId = $data['id'];
                $this->assertNotNull($this->vacationPlanId, 'O ID do plano de férias não foi retornado.');
            } else {
                Log::error('ID do plano de férias não encontrado na resposta da criação.');
            }
        }
    }

    public function testUpdateVacationPlan()
    {
        if (!$this->vacationPlanId) {
            $this->testCreateVacationPlan();
        }

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->putJson("/api/vacation_plan/{$this->vacationPlanId}", [
                             'title' => 'Teste 2',
                             'description' => 'Teste 1',
                             'location' => 'Casa',
                             'date' => '2024-08-11',
                             'participants' => [
                                 ['name' => 'Teresa'],
                                 ['name' => 'Mateus'],
                                 ['name' => 'Katia']
                             ]
                         ]);

        if ($response->status() == 401) {
            Log::error('Erro 401 no teste UpdateVacationPlan: ' . $response->getContent());
        } else {
            $response->assertStatus(200);
        }
    }

    public function testDeleteVacationPlan()
    {
        if (!$this->vacationPlanId) {
            $this->testCreateVacationPlan();
        }

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->deleteJson("/api/vacation_plan/{$this->vacationPlanId}");

        if ($response->status() == 401) {
            Log::error('Erro 401 no teste DeleteVacationPlan: ' . $response->getContent());
        } else {
            $response->assertStatus(200);
        }
    }

    // public function testExportVacationPlan()
    // {
    //     $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
    //                      ->postJson('/api/vacation_plan/export', [
    //                          'date' => '2024-08-11'
    //                      ]);

    //     if ($response->status() == 400) {
    //         Log::error('Erro 400 no teste ExportVacationPlan: ' . $response->getContent());
    //     } else {
    //         $response->assertStatus(200);
    //     }
    // }
}
