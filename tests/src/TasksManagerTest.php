<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\TasksManager;

class TasksManagerTest extends TestCase {

    private string $tempFile;

    protected function setUp(): void {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'tasks');
    }

    protected function tearDown(): void {
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }

    public function testTasksPropertyIsInitializedWithGivenResource() {

        $tasksArr = ['Tasks' => [['id' => 1, 'name' => 'Test Task']]];
        $tasksData = json_encode($tasksArr);
        file_put_contents($this->tempFile, $tasksData);

        $tasksManager = new TasksManager($this->tempFile);
        $this->assertSame($tasksArr, $tasksManager->list());

    }

    public function testTasksPropertyHasFallbackWhenResourceIsEmptyOrNonExistent() {
        $tasksManager = new TasksManager($this->tempFile);
        $this->assertSame(["Tasks" => []], $tasksManager->list());
    }

    

   
    
}