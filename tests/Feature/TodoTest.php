<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Todo;

class TodoTest extends TestCase
{

    use DatabaseMigrations;

    private $api_auth = '/api/v1/auth';
    private $api_todo = '/api/v1/todo';
    private $todo = [
        'value' => 'Example todo.',
        'status' => 'open'
    ];

    /** @test */
    public function unregisteredUserCannotStoreTodo()
    {
        $response = $this->json('POST', $this->api_todo, $this->todo);
        $response->assertStatus(401);
    }

    /** @test */
    public function registeredUserCanStoreTodo()
    {

        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->json('POST', $this->api_todo, $this->todo);
        $response->assertStatus(201);

        $this->assertDatabaseHas('todos', [
            'id' => $response->getData()->id,
        ]);
    }

    /** @test */
    public function userCanDeleteTheirTodo()
    {
        $user = factory(User::class)->create();
        $todo = factory(Todo::class)->create(['user_id' => $user->id]);

        $endpoint = $this->api_todo . '/' . $todo->id;

        $response = $this->actingAs($user)->json('DELETE', $endpoint);
        $response->assertStatus(204);

        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }

    /** @test */
    public function userCannotDeleteDifferentUserTodo()
    {
        $user = factory(User::class)->create();
        $author = factory(User::class)->create();
        $todo = factory(Todo::class)->create(['user_id' => $author->id]);

        $endpoint = $this->api_todo . '/' . $todo->id;

        $response = $this->actingAs($user)->json('DELETE', $endpoint);
        $response->assertStatus(401);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
        ]);
    }

    /** @test */
    public function userCanPatchTheirTodo()
    {
        $user = factory(User::class)->create();
        $todo = factory(Todo::class)->create([
            'user_id' => $user->id,
            'status' => 'open'
        ]);

        $endpoint = $this->api_todo . '/' . $todo->id;

        $response = $this->actingAs($user)->json('PATCH', $endpoint, ['status' => 'closed']);
        $response->assertStatus(200);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'status' => 'closed'
        ]);
    }

    /** @test */
    public function userCannotPatchDifferentUserTodo()
    {
        $user = factory(User::class)->create();
        $author = factory(User::class)->create();
        $todo = factory(Todo::class)->create([
            'user_id' => $author->id,
            'status' => 'open'
        ]);

        $endpoint = $this->api_todo . '/' . $todo->id;

        $response = $this->actingAs($user)->json('PATCH', $endpoint, ['status' => 'closed']);
        $response->assertStatus(401);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'status' => 'open'
        ]);
    }

    /** @test */
    public function userCanGetTheirTodos()
    {
        $user = factory(User::class)->create();
        $todo = factory(Todo::class, 20)->create([
            'user_id' => $user->id,
            'status' => 'open'
        ]);
        $todo = factory(Todo::class, 30)->create([
            'user_id' => $user->id,
            'status' => 'closed'
        ]);

        // Verifies todo count is correct at /todos endpoints.
        $response = $this->actingAs($user)->json('GET', $this->api_todo);
        $response->assertStatus(200);
        $this->assertEquals(50, $response->getData()->meta->total);

        // Verifies todo count is when 'open' query string is set.
        $response = $this->actingAs($user)->json('GET', $this->api_todo . '?status=open');
        $response->assertStatus(200);

        // Verifies pagination is working correctly with query string.
        $this->assertEquals(20, $response->getData()->meta->total);
        $this->assertStringContainsString('status=open&page=2', $response->getData()->links->next);

        // Verifies todo count is when 'closed' query string is set.
        $response = $this->actingAs($user)->json('GET', $this->api_todo . '?status=closed');
        $response->assertStatus(200);

        // Verifies pagination is working correctly with query string.
        $this->assertEquals(30, $response->getData()->meta->total);
        $this->assertStringContainsString('status=closed&page=2', $response->getData()->links->next);
    }
}
