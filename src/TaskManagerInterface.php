<?php

declare(strict_types=1);

namespace App;

interface TaskManagerInterface
{
    public function add(string $description);
    public function update(int $id, string $description);
    public function markInProgress(int $id);
    public function markDone(int $id);
    public function list(?TaskStatus $status);
}
