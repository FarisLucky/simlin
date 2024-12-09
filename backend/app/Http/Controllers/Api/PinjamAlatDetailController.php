<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Http\Requests\StorePinjamAlatDetailRequest;
use App\Models\MAlat;
use App\Models\MAlatDetail;
use App\Models\PinjamAlat;
use App\Models\PinjamAlatDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class PinjamAlatDetailController extends Controller
{
    use ApiResponse;

    public function store(StorePinjamAlatDetailRequest $request)
    {

        try {
            $user = auth()->user();
            $input = $request->validated();
            $store = null;

            if ($request->type == 'SATUAN') {
                $alatDetail = PinjamAlatDetail::where(function ($query) use ($input) {
                    $query->where('nota_pinjam', $input['nota_pinjam'])
                        ->where('kode_alat', $input['kode_alat']);
                })
                    ->first();

                if (!is_null($alatDetail)) {
                    throw new Exception('Alat sudah ditambahkan');
                }

                $alat = MAlat::where('kode', $input['kode_alat'])->first();
                if (is_null($alat)) {
                    throw new Exception('Master alat tidak ada');
                }

                $sisa = $alat->sisa - $input['jml'];
                if ($sisa <= 0) {
                    throw new Exception("Gagal! Jumlah Alat: {$alat->sisa} Tidak Cukup.");
                }

                $input['pinjam'] = now();
                $input['status'] = PinjamAlat::PENGAJUAN;
                $input['created_by'] = $user->getAuthIdentifier();
                $input['updated_by'] = $user->getAuthIdentifier();
                $input['updated_by_name'] = $user->username;

                DB::beginTransaction();

                $store = PinjamAlatDetail::create($input);

                DB::commit();
            } elseif ($request->type === 'BUNDLE') {
                $alatDetail = MAlatDetail::where('id_kategori', $request->id_kategori)->get();

                if (count($alatDetail) > 0) {

                    $payload = [];
                    foreach ($alatDetail as $alat) {
                        /**
                         * CHECK ALAT SUDAH DITAMBAHKAN ATAU BELUM
                         */
                        $alatDetail = PinjamAlatDetail::where(function ($query) use ($input, $alat) {
                            $query->where('nota_pinjam', $input['nota_pinjam'])
                                ->where('kode_alat', $alat->kode_alat);
                        })
                            ->first();

                        if ($alatDetail !== null) {
                            // throw new Exception('Master alat tidak ada');
                            continue;
                        }

                        $mAlat = MAlat::where('kode', $alat->kode_alat)->first();
                        if (is_null($mAlat)) {
                            // throw new Exception('Master alat tidak ada');
                            continue;
                        }

                        $sisa = $mAlat->sisa - $input['jml'];
                        if ($sisa <= 0) {
                            // throw new Exception('Master alat tidak ada');
                            continue;
                        }

                        $payload[] = [
                            'id' => (string) Uuid::uuid4(),
                            'nota_pinjam' => $input['nota_pinjam'],
                            'kode_daftar' => $input['kode_daftar'],
                            'kode_alat' => $alat->kode_alat,
                            'nama' => $alat->nama,
                            'jml' => $alat->steril,
                            'id_kategori' => $alat->id_kategori,
                            'kategori' => $alat->kategori,
                            'pinjam' => now(),
                            'status' => PinjamAlat::PENGAJUAN,
                            'created_by' => $user->getAuthIdentifier(),
                            'updated_by' => $user->getAuthIdentifier(),
                            'updated_by_name' => $user->username,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        // $store = PinjamAlatDetail::create($input);
                    }

                    DB::beginTransaction();

                    $store = PinjamAlatDetail::insert($payload);

                    DB::commit();
                }
            }

            return $this->okApiResponse($store, 'Berhasil disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function showByKodeDaftar($kode)
    {
        try {

            $linen = PinjamAlatDetail::with([
                'daftar' => function ($query) {
                    $query->selectIdx();
                }
            ])
                ->where('kode_daftar', $kode)->get();

            return $this->okApiResponse($linen, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {

            $linen = PinjamAlatDetail::where('id', $id)->firstOrFail();
            $linen->delete();

            return $this->noContentApiResponse('');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function updateQty(Request $request, $id)
    {
        try {

            $detail = PinjamAlatDetail::with('mAlat')->where('id', $id)->first();
            if($request)
            if (is_null($detail)) {
                throw new Exception('Alat Sudah Kosong/Dihapus');
            }

            if ($request->jml > $detail->jml) {
                throw new Exception("Qty Max. {$detail->jml}");
            }

            if ($request->jenis === 'jml') {
                $detail->jml = $request->jml;

            } else if ($request->jenis === 'jml_terima') {
                $detail->jml_terima = $request->jml;

            } else if ($request->jenis === 'jml_kembali') {
                $detail->jml_kembali = $request->jml;

            } else if ($request->jenis === 'jml_akhir') {
                $detail->jml_akhir = $request->jml;

            }

            $detail->save();

            return $this->okApiResponse($detail, 'Berhasil Update');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }
}
