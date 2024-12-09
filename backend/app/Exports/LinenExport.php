<?php

namespace App\Exports;

use App\Models\Daftar;
use App\Models\Linen;
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

class LinenExport implements FromView, WithDrawings, WithStyles, ShouldAutoSize, WithHeadingRow, WithStartRow, WithTitle
{

    public $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function view(): View
    {
        // dd('test');
        $start = Carbon::make($this->params['start']);
        $end = Carbon::make($this->params['end']);

        $params = [
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d'),
        ];

        DB::statement('SET sql_mode=""');

        $linens = Linen::selectRaw('DATE(selesai) as selesai_cast, kd_unit, m_unit.nama as nama_unit')
            ->addSelect([
                'sum_terima' => DB::table('linen as ln')
                    ->select(
                        DB::raw('SUM(linen_detail.jml_terima)'),
                    )
                    ->leftJoin('linen_detail','ln.nota','=','linen_detail.nota_linen')
                    ->whereColumn('ln.kd_unit','linen.kd_unit'),

                'sum_kembali' => DB::table('linen as ln')
                    ->select(
                        DB::raw('SUM(jml_kembali) as linen_kembali'),
                    )
                    ->leftJoin('linen_detail','ln.nota','=','linen_detail.nota_linen')
                    ->whereColumn('ln.kd_unit','linen.kd_unit'),

                'sum_akhir' => DB::table('linen as ln')
                    ->select(
                        DB::raw('SUM(jml_akhir) as linen_akhir'),
                    )
                    ->leftJoin('linen_detail','ln.nota','=','linen_detail.nota_linen')
                    ->whereColumn('ln.kd_unit','linen.kd_unit'),
            ])
            ->join('m_unit', 'm_unit.kode', '=', 'linen.kd_unit')
            ->where('status', LINEN::SELESAI)
            ->groupBy('selesai_cast')
            ->groupBy('kd_unit')
            ->get();


        return view('linen_export', compact('linens'), $params);
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
        return "Rekap Lembur";
    }
}
