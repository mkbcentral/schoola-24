<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RoleType extends Enum
{
    const ADMIN_SCHOOL = 'ADMIN_SCHOOL';
    const SCHOOL_MANAGER = 'SCHOOL_MANAGER';
    const SCHOOL_FINANCE = 'SCHOOL_FINANCE';
    const SCHOOL_SECRETARY = 'SCHOOL_SECRETARY';
    const SCHOOL_MONEY_COLLECTOR = 'SCHOOL_MONEY_COLLECTOR';
    const SCHOOL_TEACHER = 'SCHOOL_TEACHER';
    const SCHOOL_DIRECTOR = 'SCHOOL_DIRECTOR';
    const SCHOOL_BOSS = 'SCHOOL_BOSS';
    const APP_ADMIN = 'APP_ADMIN';
    const SCHOOL_GUARD = 'SCHOOL_GUARD';
    const ROOT = 'ROOT';

    // get the roles as an array
    public static function getRoles(): array
    {
        return [
            ['name' => self::ADMIN_SCHOOL],
            ['name' => self::SCHOOL_MANAGER],
            ['name' => self::SCHOOL_FINANCE],
            ['name' => self::SCHOOL_SECRETARY],
            ['name' => self::SCHOOL_MONEY_COLLECTOR],
            ['name' => self::SCHOOL_TEACHER],
            ['name' => self::SCHOOL_DIRECTOR],
            ['name' => self::SCHOOL_BOSS],
            ['name' => self::APP_ADMIN],
            ['name' => self::SCHOOL_GUARD],
            ['name' => self::ROOT],
        ];
    }
}
