<?php

namespace Air\Quality\Common\Trait;

trait EnumToArrayTrait
{
    /** @return string[] */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
