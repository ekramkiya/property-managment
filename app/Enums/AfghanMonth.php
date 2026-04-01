<?php

namespace App\Enums;

enum AfghanMonth: string
{
    case HAMAL = 'حمل';
    case SAUR = 'ثور';
    case JAWZA = 'جوزا';
    case SARATAN = 'سرطان';
    case ASAD = 'اسد';
    case SUNBULA = 'سنبله';
    case MIZAN = 'میزان';
    case AQRAB = 'عقرب';
    case QAWS = 'قوس';
    case JADI = 'جدی';
    case DALW = 'دلو';
    case HUT = 'حوت';

    /**
     * Get all month names as an array for Filament select.
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->value])
            ->toArray();
    }

    /**
     * Get the current Afghan month name.
     * Requires the 'jenssegers/date' package or a Jalali converter.
     */
    public static function current(): self
    {
        $jalali = \Morilog\Jalali\Jalalian::now();
        $monthNumber = $jalali->getMonth(); // 1..12
        return match($monthNumber) {
            1 => self::HAMAL,
            2 => self::SAUR,
            3 => self::JAWZA,
            4 => self::SARATAN,
            5 => self::ASAD,
            6 => self::SUNBULA,
            7 => self::MIZAN,
            8 => self::AQRAB,
            9 => self::QAWS,
            10 => self::JADI,
            11 => self::DALW,
            12 => self::HUT,
            default => self::HAMAL,
        };
    }
}