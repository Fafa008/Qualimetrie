<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MobileBillTest extends TestCase
{
    use RefreshDatabase;

    private array $basePayload = [
        'dataUsed' => 30,
        'dataNonEU_MB' => 0,
        'anciennete' => 2,
        'isEtudiant' => false
    ];

    public function test_base_bill_within_quota(): void
    {
        $response = $this->postJson('/api/mobile-bill', $this->basePayload);
        $response->assertStatus(200);
        $response->assertJson(['total' => 15]);
    }

    public function test_data_exceeds_50gb_quota(): void
    {
        $payload = array_merge($this->basePayload, ['dataUsed' => 70]);
        $response = $this->postJson('/api/mobile-bill', $payload);
        $response->assertJson(['total' => 55]);
    }

    public function test_seniority_over_5_years_increases_quota(): void
    {
        $payload = array_merge($this->basePayload, ['dataUsed' => 70, 'anciennete' => 6]);
        $response = $this->postJson('/api/mobile-bill', $payload);
        $response->assertJson(['total' => 15]);
    }

    public function test_seniority_plus_high_data(): void
    {
        $payload = array_merge($this->basePayload, ['dataUsed' => 120, 'anciennete' => 6]);
        $response = $this->postJson('/api/mobile-bill', $payload);
        $response->assertJson(['total' => 55]);
    }

    public function test_student_discount(): void
    {
        $payload = array_merge($this->basePayload, ['isEtudiant' => true]);
        $response = $this->postJson('/api/mobile-bill', $payload);
        $response->assertJson(['total' => 13.5]);
    }

    public function test_student_with_excess_data(): void
    {
        $payload = array_merge($this->basePayload, ['dataUsed' => 70, 'isEtudiant' => true]);
        $response = $this->postJson('/api/mobile-bill', $payload);
        $response->assertJson(['total' => 53.5]);
    }

    public function test_non_eu_data_charge(): void
    {
        $payload = array_merge($this->basePayload, ['dataNonEU_MB' => 5]);
        $response = $this->postJson('/api/mobile-bill', $payload);
        $response->assertJson(['total' => 40]);
    }

    public function test_non_eu_data_capped_at_50(): void
    {
        $payload = array_merge($this->basePayload, ['dataNonEU_MB' => 20]);
        $response = $this->postJson('/api/mobile-bill', $payload);
        $response->assertJson(['total' => 65]);
    }

    public function test_student_seniority_non_eu_full_scenario(): void
    {
        $payload = ['dataUsed' => 120, 'dataNonEU_MB' => 8, 'anciennete' => 6, 'isEtudiant' => true];
        $response = $this->postJson('/api/mobile-bill', $payload);
        $response->assertJson(['total' => 93.5]);
    }

    public function test_returns_json_structure(): void
    {
        $response = $this->postJson('/api/mobile-bill', $this->basePayload);
        $response->assertJsonStructure(['total']);
    }
}
