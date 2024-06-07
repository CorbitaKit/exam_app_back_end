<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected $taskService;

    public function setUp(): void
    {
        parent::setUp();
        $this->taskService = new TaskService(
            new TaskRepository(new Task)
        );
    }

    public function test_fetching_all_tasks()
    {
        $numTasks = rand(1, 20);
        Task::factory()->count($numTasks)->create();

        $tasks = $this->taskService->doGet();

        $this->assertCount($numTasks, $tasks);
    }


    public function test_fetching_single_task()
    {
        $task = Task::factory()->create();

        $returnedTask = $this->taskService->doFindById($task->id);

        $this->assertNotNull($returnedTask);
        $this->assertEquals($task->id, $returnedTask->id);
    }

    public function test_creating_task()
    {
        $user = User::factory()->create();
        $task = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'To Do',
            'user_id' => $user->id
        ];

        $createdTask = $this->taskService->doCreate($task);

        $this->assertInstanceOf(Task::class, $createdTask);
        $this->assertEquals($task['title'], $createdTask->title);
        $this->assertEquals($task['user_id'], $createdTask->user_id);
    }

    public function test_updating_task()
    {
        $task = Task::factory()->create();

        $updatedTask = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $returnedTask = $this->taskService->doUpdate($task->id, $updatedTask);

        $this->assertInstanceOf(Task::class, $returnedTask);
        $this->assertEquals($updatedTask['title'], $returnedTask['title']);
        $this->assertEquals($updatedTask['description'], $returnedTask['description']);
    }

    public function test_delete_task()
    {
        $task = Task::factory()->create();

        $isDeleted = $this->taskService->doDelete($task->id);

        $this->assertTrue($isDeleted);
        $this->assertNull(Task::find($task->id));
    }
}
