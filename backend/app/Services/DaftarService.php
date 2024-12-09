<?php

namespace App\Services;

use App\Models\Daftar;
use Illuminate\Support\Facades\Log;

class DaftarService
{
    public function store(array $params)
    {
        $payload = [
            'kode' => $params['kode'],
            'jml' => 0,
            'jenis' => strtoupper($params['request']['jenis']),
            'status' => Daftar::NOTA,
            'created_by' => $params['user']->getAuthIdentifier(),
            'created_by_name' => $params['user']->username,
            'kd_unit' => $params['user']->kd_unit,
        ];

        return Daftar::create($payload);
    }

    public static function genKode()
    {
        $mAlat = Daftar::latest()->first(['kode']);
        $kode = now()->format('ymHi-');
        $result = 0;

        if (is_null($mAlat)) {
            $result = 1;
        } else {
            $lastInc = explode("-", $mAlat->kode);

            $result = ("$lastInc[0]-" === $kode) ? $lastInc[1] + 1 : 1;
        }

        return $kode . $result;
    }
}
