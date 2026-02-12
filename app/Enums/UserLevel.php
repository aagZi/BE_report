<?php


namespace App\Enums;

enum UserLevel: int
{
    case ADMIN   = 1;
    case CLIENT  = 2;
    case TEKNISI = 3;
    case CABANG  = 4;
    case GROUP   = 5;

    public function label(): string
    {
        return match ($this) {
            self::ADMIN   => 'Admin',
            self::CLIENT  => 'Client',
            self::TEKNISI => 'Teknisi',
            self::CABANG  => 'Cabang',
            self::GROUP   => 'Group',
        };
    }
}
