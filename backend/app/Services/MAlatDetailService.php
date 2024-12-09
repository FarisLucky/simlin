<?php

namespace App\Services;

use App\Models\MAlatDetail;

class MAlatDetailService
{
    private $kodeGrub;

    public function genKode()
    {
        $detail = MAlatDetail::latest()->first(['kode']);
        $kode = $this->kodeGrub . "-";
        $result = 0;

        if (is_null($detail)) {
            $result = 1;
        } else {
            $lastInc = (int) explode("-", $detail->kode)[1];
            $result = $lastInc + 1;
        }

        return $kode . $result;
    }

    public function setKodeGrub($kode)
    {
        $this->kodeGrub = $kode;

        return $this;
    }
}
