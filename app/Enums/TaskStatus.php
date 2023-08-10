<?php

namespace App\Enums;

enum TaskStatus:string
{
    case Backlog = 'backlog';
    case Wip = 'wip';
    case Done = 'done';
    case Canceled = 'canceled';

    public static function getValues(): array
    {
        return array_column(TaskStatus::cases(), 'value');
    }
}
