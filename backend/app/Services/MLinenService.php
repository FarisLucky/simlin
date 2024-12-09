<?php

namespace App\Services;

use App\Models\MLinen;

class MLinenService
{
    public static function genKode()
    {
        $mLinen = MLinen::latest()->first(['kode']);
        $kode = "L-";
        $result = 0;

        if(is_null($mLinen)) {
            $result = 1;
        } else {
            $lastInc = (int) explode("-", $mLinen->kode)[1];
            $result = $lastInc+1;
        }

        return $kode . $result;
    }
}
