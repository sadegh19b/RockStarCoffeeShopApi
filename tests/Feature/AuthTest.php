<?php


use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /** @test */
    public function the_user_can_login(): void
    {
        $response = $this->postJson(route('api.v1.login'), [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message')
                ->has('data', function (AssertableJson $json) {
                    $json->has('token');
                });
        });

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    /** @test */
    public function the_user_cannot_login_with_incorrect_data(): void
    {
        $this->postJson(route('api.v1.login'), [
            'email' => 'test@example.com',
            'password' => '12345',
        ])->assertUnprocessable();
    }

    /** @test */
    public function the_user_can_logout(): void
    {
        \Laravel\Sanctum\Sanctum::actingAs(\App\Models\User::find(2));

        $this->deleteJson(route('api.v1.logout'))->assertOk();
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
