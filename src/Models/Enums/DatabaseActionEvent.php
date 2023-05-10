<?php declare(strict_types=1);

namespace BadChoice\Thrust\Models\Enums;

enum DatabaseActionEvent: string
{
    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';
}
