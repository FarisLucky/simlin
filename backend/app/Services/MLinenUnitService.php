<?php

namespace App\Services;

use App\Models\MLinenUnit;

class MLinenUnitService
{
    private $kodeUnit;

    public function genKode()
    {
        $mLinen = MLinenUnit::latest()->first(['kode']);
        $kode = $this->kodeUnit . "-";
        $result = 0;

        if (is_null($mLinen)) {
            $result = 1;
        } else {
            $lastInc = (int) explode("-", $mLinen->kode)[1];
            $result = $lastInc + 1;
        }

        return $kode . $result;
    }

    public function linenExists(array $params)
    {
        $linenUnit = MLinenUnit::select('id');

        foreach ($params as $key => $value) {
            if ($value) { // Check if the value is not null or false
                $linenUnit->where($key, $value);
            }
        }

        return $linenUnit->first();
    }

    public function setKodeUnit($kode)
    {
        $this->kodeUnit = $kode;

        return $this;
    }
}
