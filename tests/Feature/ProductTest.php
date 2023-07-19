<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function get_the_list_of_products_in_response(): void
    {
        $this->seed();

        $response = $this->getJson(route('api.v1.products'));

        $response->assertOk();
        $response->assertJsonCount(6, 'data');
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message')
                ->has('data', function (AssertableJson $json) {
                    $json->each(function (AssertableJson $item) {
                        $item->has('id')
                            ->has('name')
                            ->has('description')
                            ->has('price')
                            ->has('options')
                            ->etc();
                    });
                });
        });
    }
}
