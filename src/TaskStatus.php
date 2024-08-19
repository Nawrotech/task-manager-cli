<?php

declare(strict_types=1);

namespace App;

enum TaskStatus: string {
    case TODO = "todo";
    case IN_PROGRESS = "in-progress";
    case DONE = "done";
}



