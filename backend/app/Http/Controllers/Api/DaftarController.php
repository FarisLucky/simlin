<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Daftar;
use App\Models\Linen;
use App\Models\MUnit;
use App\Models\PinjamAlat;
use App\Models\User;
use App\Services\DaftarService;
use App\Services\LinenService;
use App\Services\PinjamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DaftarController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {

            $search = request('search');
            $jenis = request('jenis');
            $sortBy = request('sortBy');
            $sortType = request('sortType');
            $perPage = request('rowsPerPage');
            $user = auth()->user();

            $daftars = Daftar::with([
                'unit' => function ($query) {
                    $query->select('kode', 'nama');
                },
            ])
                ->select([
                    'id',
                    'kode',
                    'jenis',
                    'kd_unit',
                    'pengajuan',
                    'terima',
                    'kembalikan',
                    'selesai',
                    'ket',
                    'status',
                    'created_by',
                    'created_by_name',
                ])
                // SEARCH GLOBALLY
                ->when(!is_null($search), function ($query) use ($search) {
                    $query->orWhereHas('linenDetail', function ($query) use ($search) {
                        $query->where('nama', 'LIKE', "%$search%");
                    })
                        ->orWhereHas('unit', function ($query) use ($search) {
                            $query->where('nama', 'LIKE', "%$search%");
                        })
                        ->orWhere('jenis', 'LIKE', "%{$search}%")
                        ->orWhere('kode', 'LIKE', "%{$search}%");
                })
                ->when($user->role !== User::SUPER_ADMIN, function ($query) use ($user) {
                    return $query->where('kd_unit', $user->kd_unit);
                })
                ->whenJenis(strtoupper($jenis))
                ->whenSort($sortBy, $sortType)
                ->where('status', '<>', Daftar::SELESAI)
                ->paginate($perPage);

            return $this->okApiResponse(
                $daftars,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {

            DB::beginTransaction();

            $user = auth()->user();
            $jenis = $request->jenis;
            $kode = DaftarService::genKode(strtoupper($jenis));

            $payload = [
                'kode' => $kode,
                'user' => $user,
                'request' => $request->all(),
            ];

            $daftarService = new DaftarService();
            $daftarStore = $daftarService->store($payload);

            if ($daftarStore && strtoupper($jenis) === Daftar::LINEN) {
                $linenService = new LinenService();
                $payload['kode_daftar'] = optional($daftarStore)->kode;
                $payload['nama'] = Daftar::LINEN;
                $payload['nota'] = LinenService::genNota();
                $linenService->store($payload);
                $daftarStore->load('linen');
            }

            DB::commit();
            return $this->okApiResponse(
                $daftarStore,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $daftar = Daftar::with([
                'linenDetail' => function ($query) {
                    $query->select('kode_daftar', 'nama', 'jml');
                },
                'pinjamAlatDetail' => function ($query) {
                    $query->select('kode_daftar', 'nama', 'jml');
                },
                'unit' => function ($query) {
                    $query->select('kode', 'nama');
                }
            ])
                ->find($id, [
                    'id',
                    'kode',
                    'jenis',
                    'kd_unit',
                    'pengajuan',
                    'terima',
                    'kembalikan',
                    'selesai',
                    'ket',
                    'status',
                    'created_by',
                    'created_by_name',
                ]);

            return $this->okApiResponse($daftar, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function update(Request $request, $kode)
    {
        try {
            DB::beginTransaction();

            $daftar = Daftar::where('kode', $kode)->first();
            $unit = MUnit::where('nama', $request->unit)->first(['kode', 'nama']);
            $daftar->kd_unit = $unit->kode;
            if (!is_null($daftar)) {
                $daftar->penerima = $request->penerima;
                $daftar->ket = $request->ket;
            }
            if ($daftar->jenis === Daftar::LINEN) {
                $linen = Linen::where('kode_daftar', $kode)->first();
                if (!is_null($linen)) {
                    $linen->update([
                        'berat' => $request->berat
                    ]);
                }
            }
            $daftar->save();

            DB::commit();

            return $this->okApiResponse($daftar, 'Berhasil dimuat');
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorApiResponse($th->getTraceAsString());
        }
    }

    public function showBy()
    {
        try {
            $kodeDaftar = request('kode_daftar');
            $jenis = strtoupper(request('jenis'));

            $daftar = Daftar::with('unit', 'linen')
                ->where('jenis', $jenis)
                ->byKodeDaftar($kodeDaftar)
                ->first();

            return $this->okApiResponse($daftar, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function updateAjukan(Request $request, $kode)
    {
        try {
            $daftar = Daftar::with(['pinjamAlat', 'linen'])->where('kode', $kode)->first();
            $payload = [
                'status' => $request->progress
            ];
            if ($daftar->jenis === Daftar::LINEN) {
                switch ($request->progress) {
                    case Daftar::PENGAJUAN:
                        $payload['pengajuan'] = now();
                        $payload['status'] = Daftar::PENGAJUAN;
                        break;
                    case Daftar::TERIMA:
                        $payload['terima'] = now();
                        $payload['status'] = Daftar::TERIMA;
                        break;
                    case Daftar::DIKEMBALIKAN:
                        $payload['kembalikan'] = now();
                        $payload['status'] = Daftar::DIKEMBALIKAN;
                        break;
                    case Daftar::SELESAI:
                        $payload['selesai'] = now();
                        $payload['status'] = Daftar::SELESAI;
                        $daftar->linen->update(['status' => PinjamAlat::SELESAI]);
                        // if (count($daftar->linen) > 0) {
                        //     foreach ($daftar->linen as $linen) {
                        //         $linen->update(['status' => PinjamAlat::SELESAI]);
                        //     }
                        // }
                        break;
                        // $daftar->linen->update([
                        //     'status' => Linen::SELESAI,
                        //     'selesai' => now()
                        // ]);
                }
            } else if ($daftar->jenis === Daftar::ALAT) {
                switch ($request->progress) {
                    case Daftar::PENGAJUAN:
                        $payload['pengajuan'] = now();
                        $payload['status'] = Daftar::PENGAJUAN;
                        break;
                    case Daftar::TERIMA:
                        $payload['terima'] = now();
                        $payload['status'] = Daftar::TERIMA;
                        break;
                    case Daftar::DIKEMBALIKAN:
                        $payload['kembalikan'] = now();
                        $payload['status'] = Daftar::DIKEMBALIKAN;
                        PinjamService::updateDetail($daftar->pinjamAlat);
                        break;
                    case Daftar::SELESAI:
                        $payload['selesai'] = now();
                        $payload['status'] = Daftar::SELESAI;
                        if (count($daftar->pinjamAlat) > 0) {
                            foreach ($daftar->pinjamAlat as $pinjam) {
                                $pinjam->update(['status' => PinjamAlat::SELESAI]);
                            }
                        }
                        break;
                        // case Daftar::SELESAI:
                        //     $payload['selesai'] = now();
                        //     $payload['status'] = Daftar::SELESAI;
                        //     break;
                }
            }

            $daftar->update($payload);

            return $this->okApiResponse($daftar, 'Berhasil diperbarui');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {

            $daftar = Daftar::where('id', $id)->first();
            if (is_null($daftar)) {
                throw new \Exception('Data sudah tidak ada');
            }
            $daftar->delete();

            return $this->noContentApiResponse('');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function destroyAll()
    {
        try {
            $ids = request('ids');
            $inIds = explode(',', $ids);
            $daftars = Daftar::whereIn('id', $inIds)->get();

            if ($daftars->isEmpty()) {
                throw new \Exception(print_r($inIds));
            }

            foreach ($daftars as $daftar) {
                $daftar->delete();
            }

            return $this->noContentApiResponse('');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function grafik()
    {
        try {

            $linen = Daftar::selectRaw("COUNT(*) as ttl, DATE(pengajuan) AS pengajuan_date")
                ->whereBetween('pengajuan', [
                    now()->subDays(10)->format('Y-m-d'),
                    now()->format('Y-m-d')
                ])
                ->where('jenis', Daftar::ALAT)
                ->orderBy('pengajuan')
                ->groupBy('pengajuan')
                ->get();

            $alat = Daftar::selectRaw("COUNT(*) as ttl, DATE(pengajuan) AS pengajuan_date")
                ->whereBetween('pengajuan', [
                    now()->subDays(10)->format('Y-m-d'),
                    now()->format('Y-m-d')
                ])
                ->where('jenis', Daftar::ALAT)
                ->orderBy('pengajuan_date')
                ->groupBy('pengajuan_date')
                ->get();

            $result = [
                'label' => $linen->pluck('pengajuan_date'),
                'series' => [
                    'linen' => $linen->pluck('ttl'),
                    'alat' => $alat->pluck('ttl')
                ],
            ];

            return $this->okApiResponse($result, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function statistik()
    {
        try {

            /**
             * Grafik card
             */
            $result = [
                'grafik_card' => [
                    'blm_selesai' => DB::table('daftar')
                    ->selectRaw('COUNT(id) as blm_selesai')
                    ->where('status', '<>', Daftar::SELESAI)
                    ->where('status', '<>', Daftar::NOTA)
                    ->pluck('blm_selesai')
                    ->first(),

                    'sum_selesai' => DB::table('daftar')
                    ->selectRaw('COUNT(id) as st_selesai')
                    ->where('status', Daftar::SELESAI)
                    ->pluck('st_selesai')
                    ->first(),

                    'count_alat' => DB::table('m_alat')
                        ->selectRaw('COUNT(*) as c_alat')->pluck('c_alat')[0],

                    'count_bundle' => DB::table('m_bundle')
                        ->selectRaw('COUNT(id) as c_bundle')->pluck('c_bundle')[0],
                ],
                // 'grafik_line' => $line,
            ];

            return $this->okApiResponse($result, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }
}
