<?php

namespace App\Enums;

enum UserRole: string
{
    case Employee = 'employee';
    case Admin = 'admin';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(
            static fn (self $role): string => $role->value,
            self::cases(),
        );
    }
}
