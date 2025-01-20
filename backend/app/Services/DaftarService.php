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

    public static function genKode($jenis)
    {
        $kdDaftar = Daftar::latest()
            ->first(['kode']);

        if ($jenis === Daftar::LINEN) {
            $kode = 'L' . now()->format('ym-');
        } else {
            $kode = 'A' . now()->format('ym-');
        }

        $result = 0;

        if (is_null($kdDaftar)) {
            $result = 1;
        } else {
            $lastInc = explode("-", $kdDaftar->kode);

            $result = intval($lastInc[1]) + 1;
        }

        return $kode . $result;
    }
}
