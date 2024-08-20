<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\TaskManager;
use App\TaskStatus;

class TaskManagerTest extends TestCase
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

        $tasksArr = ["Tasks" => [['id' => 1, 'name' => 'Test Task']]];
        $tasksData = json_encode($tasksArr);
        file_put_contents($this->tempFile, $tasksData);

        $tasksManager = new TaskManager($this->tempFile);
        $this->assertSame($tasksArr["Tasks"], $tasksManager->list());
    }

    public function testTasksPropertyHasFallbackWhenResourceIsEmptyOrNonExistent()
    {
        $tasksManager = new TaskManager($this->tempFile);
        $this->assertEmpty($tasksManager->list());
    }



    public function testTaskOfGivenDescriptionIsAdded()
    {
        $tasksManager =  new TaskManager($this->tempFile);
        $tasksManager->add("foo");

        $tasks = $tasksManager->list();
        $this->assertCount(1, $tasks);

        $addedTask = $tasks[0];
        $this->assertSame("foo", $addedTask["description"]);
    }

    public function testMultipleTasksAreAdded()
    {
        $tasksManager =  new TaskManager($this->tempFile);
        $tasksManager->add("foo");
        $tasksManager->add("bar");
        $tasksManager->add("baz");

        $tasks = $tasksManager->list();
        $this->assertCount(3, $tasks);

        $lastTask = $tasks[2];
        $this->assertSame(3, $lastTask["id"]);
    }

    public function testTaskIsMarkedAsDone()
    {
        $tasksManager =  new TaskManager($this->tempFile);
        $tasksManager->add("foo");

        $tasksManager->markDone(1);
        $tasks = $tasksManager->list();

        $this->assertSame("done", $tasks[0]["status"]);
    }

    public function testTaskIsMarkedAsInProgress()
    {
        $tasksManager =  new TaskManager($this->tempFile);
        $tasksManager->add("foo");

        $tasksManager->markInProgress(1);
        $tasks = $tasksManager->list();

        $this->assertSame("in-progress", $tasks[0]["status"]);
    }

    public function testTaskIsDeleted()
    {
        $tasksManager =  new TaskManager($this->tempFile);
        $tasksManager->add("foo");;

        $this->assertCount(1,  $tasksManager->list());

        $tasksManager->delete(1);
        $this->assertCount(0, $tasksManager->list());
    }

    public function testAddedTasksAreListed()
    {
        $tasksManager =  new TaskManager($this->tempFile);
        $tasksManager->add("foo");
        $tasksManager->add("bar");
        $tasksManager->add("baz");

        $this->assertCount(3, $tasksManager->list());
    }

    public function testTasksAreFiltered()
    {
        $tasksManager =  new TaskManager($this->tempFile);
        $tasksManager->add("foo");
        $tasksManager->add("bar");
        $tasksManager->add("baz");
        $tasksManager->add("foo");
        $tasksManager->add("baz");
        $this->assertCount(5, $tasksManager->list(TaskStatus::TODO));

        $tasksManager->markInProgress(1);
        $tasksManager->markInProgress(2);
        $tasksManager->markDone(3);
        $tasksManager->markDone(4);
        $tasksManager->markDone(5);
        $this->assertCount(2, $tasksManager->list(TaskStatus::IN_PROGRESS));
        $this->assertCount(3, $tasksManager->list(TaskStatus::DONE));
    }

    public function testTaskDescriptionIsUpdated()
    {
        $tasksManager =  new TaskManager($this->tempFile);
        $tasksManager->add("foo");

        $tasksManager->list();
        $this->assertSame("foo", $tasksManager->list()[0]["description"]);

        $tasksManager->update(1, "foobarbaz");
        $this->assertSame("foobarbaz", $tasksManager->list()[0]["description"]);
    }
}
