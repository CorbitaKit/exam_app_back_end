<?php

namespace Tests\Feature\GraphQL;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Nuwave\Lighthouse\Testing\RefreshesSchemaCache;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase, MakesGraphQLRequests, RefreshesSchemaCache, WithFaker;

    public function test_if_can_fetch_task_list()
    {
        Task::factory()->count(10)->create();
        $response = $this->graphQL('{
            tasks{
                id,
                title,
                description,
                created_at,
                updated_at
            }
        }');

        $response->assertJsonStructure([
            'data' => [
                'tasks' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'created_at',
                        'updated_at'
                    ]

                ]
            ]
        ]);
    }

    public function test_if_can_fetch_single_task_record()
    {
        $task = Task::factory()->create();
        $response = $this->graphQL("{
            task(id: {$task->id}){
                id,
                title,
                description,
                created_at,
                updated_at
            }
        }");

        $response->assertJsonStructure([
            'data' => [

                'task' => [
                    'id',
                    'title',
                    'description',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }

    public function test_if_can_create_task()
    {
        $task = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => "To Do",
            'user_id' => 1
        ];

        $response = $this->graphQL(
            "
        mutation CreateTask(\$input: TaskCreationInput!) {
            createTask(input: \$input) {
                id,
                title,
                description,
                status,
                user_id,
                created_at,
                updated_at
            }
        }
       ",
            [
                'input' => $task
            ]
        );

        $response->assertJsonStructure([
            'data' => [
                'createTask' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'user_id',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
        $this->assertDatabaseHas('tasks', $task);
    }

    public function test_if_can_update_task()
    {
        $task = Task::factory()->create();

        $updatedTask = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $response = $this->graphQL(
            "
        mutation UpdateTask(\$input: TaskUpdateInput!) {
            updateTask(id: {$task->id}, input: \$input) {
                id,
                title,
                description,
                status,
                user_id,
                created_at,
                updated_at
            }
        }
       ",
            [
                'input' => $updatedTask
            ]
        );

        $response->assertJsonStructure([
            'data' => [
                'updateTask' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'user_id',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => $updatedTask['title'],
            'description' => $updatedTask['description']
        ]);
    }

    public function test_if_can_delete_task()
    {
        $task = Task::factory()->create();
        $response = $this->graphQL('
        mutation DeleteTask($id: ID!) {
            deleteTask(id: $id)
        }
    ', ['id' => $task->id]);

        $response->assertJson([
            'data' => [
                'deleteTask' => true,
            ],
        ]);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
