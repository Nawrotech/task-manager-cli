<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\TasksManager;

class TasksManagerTest extends TestCase
{

    private string $tempFile;

    protected function setUp(): void
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'tasks');
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }

    public function testTasksPropertyIsInitializedWithGivenResource()
    {

        $tasksArr = ['Tasks' => [['id' => 1, 'name' => 'Test Task']]];
        $tasksData = json_encode($tasksArr);
        file_put_contents($this->tempFile, $tasksData);

        $tasksManager = new TasksManager($this->tempFile);
        $this->assertSame($tasksArr, $tasksManager->list());
    }

    public function testTasksPropertyHasFallbackWhenResourceIsEmptyOrNonExistent()
    {
        $tasksManager = new TasksManager($this->tempFile);
        $this->assertSame(["Tasks" => []], $tasksManager->list());
    }



    public function testTaskOfGivenDescriptionIsAdded()
    {
        $tasksManager =  new TasksManager($this->tempFile);
        $tasksManager->add("foo");

        $tasks = $tasksManager->list()["Tasks"];
        $this->assertCount(1, $tasks);

        $addedTask = $tasks[0];
        $this->assertSame("foo", $addedTask["description"]);
    }

    public function testMultipleTasksAreAdded()
    {
        $tasksManager =  new TasksManager($this->tempFile);
        $tasksManager->add("foo");
        $tasksManager->add("bar");
        $tasksManager->add("baz");

        $tasks = $tasksManager->list()["Tasks"];
        $this->assertCount(3, $tasks);

        $lastTask = $tasks[2];
        $this->assertSame(3, $lastTask["id"]);
    }

    public function testTaskIsMarkedAsDone()
    {
        $tasksManager =  new TasksManager($this->tempFile);
        $tasksManager->add("foo");

        $tasksManager->markDone(1);
        $tasks = $tasksManager->list()["Tasks"];

        $this->assertSame("done", $tasks[0]["status"]);
    }
}
