<?php

namespace App\Services;

use App\Models\MAlat;

class MAlatService
{
    public static function genKode()
    {
        $mAlat = MAlat::latest()->first(['kode']);
        $kode = "AL-";
        $result = 0;

        if (is_null($mAlat)) {
            $result = 1;
        } else {
            $lastInc = (int) explode("-", $mAlat->kode)[1];
            $result = $lastInc + 1;
        }

        return $kode . $result;
    }
}
