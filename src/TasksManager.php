<?php

declare(strict_types=1);

namespace App;

class TasksManager implements TasksManagerInterface
{

    private array $tasks;

    public function __construct(
        private string $tasksPath = DATA_PATH . "/tasks.json"
    ) {

        if (file_exists($this->tasksPath) && filesize($this->tasksPath)) {
            $this->tasks = json_decode(file_get_contents($this->tasksPath), true);
        } else {
            $tasks["Tasks"] = [];
            $this->tasks = $tasks;
        }
    }


    public function add(string $description): string
    {
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

    public function update(int $id, string $description)
    {
        return $this->updateTaskProperty($id, "description", $description);
    }

    public function delete(int $id)
    {
        try {
            $taskIndex = $this->findArrayIndexByTaskId($id);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        unset($this->tasks["Tasks"][$taskIndex]);

        $this->tasks["Tasks"] = array_values($this->tasks["Tasks"]);

        $this->applyChanges();
        return "Task deleted (ID: $id)";
    }

    public function markInProgress(int $id)
    {
        return $this->updateTaskProperty($id, "status", "in-progress");
    }

    public function markDone(int $id)
    {
        return $this->updateTaskProperty($id, "status", "done");
    }


    public function list(?TaskStatus $status = null)
    {
        if (!$status) {
            return $this->tasks["Tasks"];
        }

        return $this->filterByStatus($status);
    }

    private function filterByStatus(TaskStatus $status)
    {
        return array_filter(
            $this->tasks["Tasks"],
            fn($el) => $el["status"] === $status->value
        );
    }


    private function findArrayIndexByTaskId(int $id): int
    {
        $tasks = $this->tasks["Tasks"];
        $taskIds = array_column($tasks, "id");
        $taskArrayIndex = array_search($id, $taskIds, true);

        if ($taskArrayIndex === false) {
            throw new \Exception("Task of given ID: $id does not exist");
        }

        return $taskArrayIndex;
    }


    private function updateTaskProperty(int $id, string $key, string $value)
    {
        try {
            $index = $this->findArrayIndexByTaskId($id);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $task = &$this->tasks["Tasks"][$index];
        $task[$key] = $value;
        $task["updatedAt"] = (new \DateTime())->format("Y-m-d H:i:s");
        $this->applyChanges();
    }

    private function getLastTaskId()
    {
        return end($this->tasks["Tasks"])["id"];
    }

    private function applyChanges(): void
    {
        file_put_contents($this->tasksPath, json_encode($this->tasks), JSON_PRETTY_PRINT);
        $this->tasks = json_decode(file_get_contents($this->tasksPath), true);
    }
}
