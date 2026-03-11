<?php

namespace App\Enums;

enum Mapel: string {
    case MTK = 'Matematika';
    case IND = 'Bahasa Indonesia';
    case ING = 'Bahasa Inggris';
    case IPA = 'Pemograman GIM';
    case IPS = 'Pemograman WEB';
    case SNB = 'Pemograman Mobile';
    case PAI = 'Agama Islam';
    case PJOK = 'Olahraga';

    public static function all(): array {
        return array_column(self::cases(), 'value');
    }
}