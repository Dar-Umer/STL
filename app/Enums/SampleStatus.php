<?php

namespace App\Enums;

enum SampleStatus: string
{
    case Collected = 'collected';
    case Received = 'received';
    case Testing = 'testing';
    case Reported = 'reported';

    public function label(): string
    {
        return match ($this) {
            self::Collected => 'Collected',
            self::Received => 'Received',
            self::Testing => 'Testing',
            self::Reported => 'Reported',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
