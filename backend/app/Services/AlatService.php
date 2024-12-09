<?php

namespace App\Services;

use App\Models\PinjamAlat;

class AlatService
{
    public function store(array $params)
    {
        $payload = [
            'nama' => $params['nama'],
            'nota' => $params['nota'],
            'kode_daftar' => $params['kode_daftar'],
            'jml' => 0,
            'pinjam' => now()->format('Y-m-d H:i:s'),
            'jenis' => $params['request']['jenis'],
            'status' => PinjamAlat::PENGAJUAN,
            'kd_unit' => $params['user']->kd_unit,
            'created_by' => $params['user']->getAuthIdentifier(),
            'updated_by' => $params['user']->getAuthIdentifier(),
            'updated_by_name' => $params['user']->username,
        ];

        return PinjamAlat::create($payload);
    }

    public static function genNota()
    {
        // $linen = PinjamAlat::latest()->first(['nota']);
        // $kode = now()->format('ymHi-');
        // $result = 0;

        // if (is_null($linen)) {
        //     $result = 1;
        // } else {
        //     $lastInc = (int) explode("-", $linen->nota)[1];
        //     $result = $lastInc + 1;
        // }

        return intval(microtime(true)*1000);
    }
}
