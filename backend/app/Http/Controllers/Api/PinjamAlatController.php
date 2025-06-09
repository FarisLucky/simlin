<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Http\Requests\StorePinjamAlatRequest;
use App\Models\Catatan;
use App\Models\MBundle;
use App\Models\PinjamAlat;
use App\Models\User;
use App\Services\PinjamService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PinjamAlatController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {

            $search = request('search');
            $sortBy = request('sortBy');
            $sortType = request('sortType');
            $perPage = request('rowsPerPage');
            // $page = request('page');

            $mKategories = PinjamAlat::whenSearch($search)
                ->whenSort($sortBy, $sortType)
                ->paginate($perPage);

            return $this->okApiResponse(
                $mKategories,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function data()
    {
        try {

            $mKategories = PinjamAlat::selectIdx()
                ->where('kode_daftar')
                ->get();

            return $this->okApiResponse(
                $mKategories,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function store(StorePinjamAlatRequest $request)
    {

        try {
            $user = auth()->user();
            $input = $request->validated();

            $payloads = [];
            for ($i = 0; $i < $input['jml']; $i++) {
                $input['nota'] = PinjamService::genNota();
                $input['pinjam'] = now();
                $input['status'] = PinjamAlat::PENGAJUAN;
                $input['kd_unit'] = $user->kd_unit;
                $input['created_by'] = $user->id;
                $input['created_by_name'] = $user->username;
                $input['updated_by'] = $user->id;
                $input['updated_by_name'] = $user->username;
                $payloads[] = $input;
                PinjamAlat::create($input);
            }


            return $this->okApiResponse($payloads, 'Berhasil disimpan');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function show($id)
    {
        try {

            $mKategori = PinjamAlat::find($id);

            return $this->okApiResponse($mKategori, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function showByKodeDaftar($kode)
    {
        try {
            $user = auth()->user();

            $pinjamAlat = PinjamAlat::where('kode_daftar', $kode)
                ->when($user->role === User::SUPER_ADMIN, function ($query) {
                    $query->with([
                        'detail' => function ($query) {
                            $query->select('nota_pinjam', 'id', 'nama', 'jml');
                        },
                        'bundleDetail' => function ($query) {
                            $query->select('id_bundle', 'id', 'alat', 'jml');
                        },
                        'catatan' => function ($query) {
                            $query->select('id', 'id_relation', 'ket');
                        }
                    ]);
                })
                ->select('id', 'kode_daftar', 'nota', 'id_bundle', 'bundle', 'id_kategori', 'nama', 'pinjam')
                ->orderByDesc('created_at')
                ->get();

            return $this->okApiResponse($pinjamAlat, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {

            DB::beginTransaction();
            $pinjam = PinjamAlat::where('id', $id)->firstOrFail();
            $pinjam->id_bundle = $request->id_bundle;
            $pinjam->bundle = $request->bundle;
            $pinjam->save();

            /**
             * update master bundle
             */
            $mBundle = MBundle::where('id', $request->id_bundle)->first();
            if (!is_null($mBundle->dipinjam) && $mBundle->dipinjam !== $pinjam->kd_unit) {
                throw new Exception("Bundle {$mBundle->nama} sudah dipinjam {$pinjam->kd_unit}");
            }

            DB::commit();
            return $this->okApiResponse($pinjam, 'Berhasil diperbarui');
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {

            $pinjam = PinjamAlat::where('id', $id)->firstOrFail();
            $pinjam->delete();

            return $this->noContentApiResponse('OK');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function destroyAll()
    {
        try {

            $pinjams = PinjamAlat::whereIn('id', explode(',', request('id')))->get();
            if (count($pinjams) > 0) {
                foreach ($pinjams as $pinjam) {
                    $pinjam->delete();
                }
            }

            return $this->noContentApiResponse('OK');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function catatan(Request $request, $id)
    {
        try {

            $pinjam = PinjamAlat::where('id', $id)->first();
            if (!is_null($pinjam)) {
                Catatan::create([
                    'id_relation' => $pinjam->id,
                    'ket' => $request->ket,
                ]);
            }

            return $this->noContentApiResponse('OK');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }
}
