<?php

declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";

define("DATA_PATH", dirname(__DIR__) . "/data");

// echo DATA_PATH;

use App\TaskManager;
use App\TasksManager;
use App\TaskStatus;

// var_dump(file_exists(DATA_PATH . "/tasks.json"));


// echo TaskStatus::IN_PROGRESS->value;

$taskManager = new TasksManager();
$taskManager->add("throw out rubbish");



// $line = readline("Write something: ");
// echo $line;


