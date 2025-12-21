<?php

namespace App\Traits;

trait SuratHelper
{
    protected function bulanRomawi(int $bulan): string
    {
        $romawi = [
            'I', 'II', 'III', 'IV', 'V', 'VI',
            'VII', 'VIII', 'IX', 'X', 'XI', 'XII'
        ];

        return $romawi[$bulan - 1] ?? '';
    }
}
