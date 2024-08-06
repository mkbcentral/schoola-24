<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SchoolEnum extends Enum
{
    const PENDING = 'PENDING';
    const APPROVED = 'APPROVED';
    const REJECTED = 'REJECTED';
    const SUSPENDED = 'SUSPENDED';
}
