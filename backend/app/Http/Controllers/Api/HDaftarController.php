<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Daftar;
use App\Models\User;
use App\Services\DaftarService;
use App\Services\LinenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HDaftarController extends Controller
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
                'mutu' => function ($query) {
                    $query->select('kode_daftar');
                }
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
                        ->orWhere('jenis', 'LIKE', "%{$search}%");
                })
                ->when($user->role !== User::SUPER_ADMIN, function ($query) use ($user) {
                    return $query->where('kd_unit', $user->kd_unit);
                })
                ->whenJenis($jenis)
                ->whenSort($sortBy, $sortType)
                ->where('status', Daftar::SELESAI)
                ->paginate($perPage);

            return $this->okApiResponse(
                $daftars,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
            // return $this->errorApiResponse($th->getTraceAsString());
        }
    }

    public function store(Request $request)
    {
        try {

            DB::beginTransaction();

            $kode = DaftarService::genKode();
            $user = auth()->user();

            $payload = [
                'kode' => $kode,
                'user' => $user,
                'request' => $request->all(),
            ];

            $daftarService = new DaftarService();
            $daftarStore = $daftarService->store($payload);

            if ($daftarStore && strtoupper($request->jenis) === Daftar::LINEN) {
                $linenService = new LinenService();
                $payload['kode_daftar'] = optional($daftarStore)->kode;
                $payload['nama'] = optional($daftarStore)->jenis;
                $payload['nota'] = LinenService::genNota();
                $linenService->store($payload);
            }

            DB::commit();
            return $this->okApiResponse(
                $daftarStore,
                'Berhasil Memuat Data'
            );
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

            $daftar = Daftar::when($jenis !== null, function ($query) use ($jenis) {
                if ($jenis === Daftar::LINEN) {
                    return $query->with('linen', 'linen.mUnit')->where('jenis', $jenis);
                } else if ($jenis === Daftar::ALAT) {
                    return $query->with('pinjamAlat', 'pinjamAlat.mUnit')->where('jenis', $jenis);
                }
            })
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
            $daftar = Daftar::where('kode', $kode)->first();
            $payload = [
                'status' => $request->progress
            ];
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
                    break;
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
            $daftar = Daftar::whereIn('id', $inIds)->get();

            if ($daftar->isEmpty()) {
                throw new \Exception(print_r($inIds));
            }

            $daftar->each->delete();

            return $this->noContentApiResponse('');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }
}
