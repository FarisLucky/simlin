<?php

namespace App\Http\Controllers\Api;

use App\Exports\LinenExport;
use App\Exports\MutuExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use Maatwebsite\Excel\Facades\Excel;

class RekapController extends Controller
{
    use ApiResponse;

    public function harianLinen()
    {
        try {

            $tgl = request('range_tanggal');
            $unit = request('unit');

            $splitTgl = explode('to', $tgl);
            $start = trim($splitTgl[0]);
            $end = trim($splitTgl[1]);

            $params = [
                'start' => $start,
                'end' => $end,
                'id_unit' => $unit,
            ];
            // dd($params);

            $linen = new LinenExport($params);

            return Excel::download($linen, "linen export.xlsx");
        } catch (\Throwable $th) {

            var_dump(500, $th->getMessage());
            die();
        }
    }

    public function mutuLinen()
    {
        try {

            $tgl = request('range_tanggal');
            $unit = request('unit');

            $splitTgl = explode('to', $tgl);
            $start = trim($splitTgl[0]);
            $end = trim($splitTgl[1]);

            $params = [
                'start' => $start,
                'end' => $end,
                'units' => $unit,
            ];

            $mutu = new MutuExport($params);

            return Excel::download($mutu, "mutu linen export.xlsx");
        } catch (\Throwable $th) {

            // var_dump(500, $th->getMessage());
            dd($th);
        }
    }

}
