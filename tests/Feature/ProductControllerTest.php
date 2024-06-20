<?php

namespace Tests\Feature;

use App\Services\RabbitService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStore()
    {
        $rabbitService = Mockery::mock(RabbitService::class);
        $rabbitService->shouldReceive('sendMessage')->once();

        $this->app->instance(RabbitService::class, $rabbitService);

        $response = $this->postJson('/api/products', ['name' => 'Test']);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'Product created.',
                'data' => [
                    'name' => 'Test',
                ],
            ]);

        $this->assertDatabaseHas('products', ['name' => 'Test']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
