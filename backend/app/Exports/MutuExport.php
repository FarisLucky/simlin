<?php

namespace App\Exports;

use App\Models\Mutu;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MutuExport implements FromView, WithDrawings, WithStyles, ShouldAutoSize, WithHeadingRow, WithStartRow, WithTitle
{

    public $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function view(): View
    {
        $start = Carbon::make($this->params['start']);
        $end = Carbon::make($this->params['end']);
        $units = $this->params['units'];

        $params = [
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d'),
        ];

        DB::statement('SET sql_mode=""');

        $mutu = Mutu::leftJoin('daftar', 'daftar.kode', '=', 'mutu.kode_daftar')
            ->leftJoin('m_unit', 'm_unit.kode', '=', 'daftar.kd_unit')
            ->when(!is_null($start) && !is_null($end), function ($query) use ($start, $end) {
                $query->whereDate('daftar.pengajuan', '>', $start->format('Y-m-d'))
                    ->whereDate('daftar.pengajuan', '<', $end->format('Y-m-d'));
            })
            ->when(!is_null($units) && count($units) > 0, function ($query) use ($units) {
                $all = false;
                foreach ($units as $unit) {
                    if ($unit === 'all') {
                        $all = true;
                        break;
                    }
                }
                if (!$all) {
                    $query->whereIn('daftar.kd_unit', $units);
                }
            })
            ->get([
                'mutu.id',
                'mutu.kode_daftar',
                'daftar.pengajuan',
                'mutu.tdk_noda',
                'mutu.tdk_bau',
                'mutu.tdk_pudar',
                'mutu.tdk_rapi',
                'mutu.tdk_kualitas',
                'mutu.jml_rusak',
                'mutu.ttl',
                DB::raw('m_unit.nama as nama_unit'),
            ]);
        // dd($mutu);


        return view('mutu_export', compact('mutu'), $params);
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('RS Graha Sehat');
        $drawing->setDescription('RS Graha Sehat Kraksaan');
        $drawing->setPath(public_path('/logo_polos.png'));
        $drawing->setWidth(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function styles(Worksheet $sheet)
    {

        return [
            'B1'  => ['font' => ['size' => 18, 'bold' => true]],
            'B2'  => ['font' => ['size' => 20, 'bold' => true]],
            'B3'  => ['font' => ['size' => 14]],
            'A7:Z7'  => ['font' => ['size' => 12, 'bold' => true]],
        ];
    }

    public function headingRow(): int
    {
        return 6;
    }
    public function startRow(): int
    {
        return 6;
    }
    public function title(): string
    {
        return "Rekap Mutu";
    }
}
