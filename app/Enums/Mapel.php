<?php

namespace App\Enums;

enum Mapel: string {
    case MTK = 'Matematika';
    case IND = 'Bahasa Indonesia';
    case ING = 'Bahasa Inggris';
    case IPA = 'IPA';
    case IPS = 'IPS';
    case PAI = 'Agama Islam';
    case PJOK = 'Olahraga';
    case SNB = 'Seni Budaya';

    public static function all(): array {
        return array_column(self::cases(), 'value');
    }
}