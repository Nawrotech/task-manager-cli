<?php

declare(strict_types=1);

namespace App;

class TasksManager {

    private array $tasks;


    public function __construct(
        private string $tasksPath = DATA_PATH . "/tasks.json"
        )
    {   

        if (file_exists($this->tasksPath) && filesize($this->tasksPath)) {
            $this->tasks = json_decode(file_get_contents($this->tasksPath), true);
        } else {
            $tasks["Tasks"] = [];
            $this->tasks = $tasks;
        }

    }


    public function add(string $description): string {
        $id = $this->getLastTaskId() ?? 0;
        $id++;

        $newTask = [
            "id" => $id,
            "description" => $description,
            "status" => "todo",
            "createdAt" => (new \DateTime())->format("Y-m-d H:i:s"),
            "updatedAt" => null
        ];

        $this->tasks["Tasks"][] = $newTask;
        $this->applyChanges();
        return "Task added successfully (ID: $id)";
      
    }

    public function update(int $id, string $description) {
        $this->updateTaskProperty($id, "description", $description);
    }

    public function delete(int $id) {
        $taskIndex = $this->findArrayIndexByTaskId($id);
        if (isset($taskIndex)) {
            unset($this->tasks["Tasks"][$taskIndex]);
            $this->applyChanges();
        } else {
            echo "Taks of given $id does not exist...";
        }

    }

    public function markInProgress(int $id) {
        $this->updateTaskProperty($id, "status", "in-progress");
    }

    public function markDone(int $id) {
        $this->updateTaskProperty($id, "status", "done");
    }


    public function list(?TaskStatus $status = null) {
        if (!$status) {
            return $this->tasks;
        }

        return $this->filterByStatus($status);

    }

    private function filterByStatus(TaskStatus $status = TaskStatus::TODO->value) {
        return array_filter($this->tasks["Tasks"], 
                fn($el) => $el["status"] === $status->value);
    }


    private function findArrayIndexByTaskId(int $id) {
        $tasks = $this->tasks["Tasks"];
        $taskIds = array_column($tasks, "id");
        $taskArrayIndex = array_search($id, $taskIds);
        return $taskArrayIndex;
    }

    private function &getTaskById(int $id) {
        $index = $this->findArrayIndexByTaskId($id);
        return $this->tasks["Tasks"][$index];
                
    }


    private function updateTaskProperty(int $id, string $key, string $value) {
        $task = &$this->getTaskById($id);
        $task[$key] = $value;
        $task["updatedAt"] = (new \DateTime())->format("Y-m-d H:i:s");
        $this->applyChanges();
    }

    private function getLastTaskId() {
        return end($this->tasks["Tasks"])["id"];
    }

    private function applyChanges(): void {
        file_put_contents($this->tasksPath, json_encode($this->tasks));
        $this->tasks = json_decode(file_get_contents($this->tasksPath), true);
    }






    


}