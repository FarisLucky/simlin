<?php

namespace App\Services;

use App\Models\Linen;

class LinenService
{
    public function store(array $params)
    {
        $payload = [
            'nama' => $params['nama'],
            'nota' => $params['nota'],
            'kode_daftar' => $params['kode_daftar'],
            'jml' => 0,
            'berat' => 0,
            'jenis' => $params['request']['jenis'],
            'status' => Linen::PENGAJUAN,
            'kd_unit' => $params['user']->kd_unit,
            'created_by' => $params['user']->getAuthIdentifier(),
            'updated_by' => $params['user']->getAuthIdentifier(),
            'updated_by_name' => $params['user']->username,
        ];

        return Linen::create($payload);
    }

    public static function genNota()
    {
        $linen = Linen::latest()->first(['nota']);
        $kode = now()->format('Ym-');
        $result = 0;

        if (is_null($linen)) {
            $result = 1;
        } else {
            $lastInc = (int) explode("-", $linen->nota)[1];
            $result = $lastInc + 1;
        }

        return "LN$kode" . $result;
    }
}
