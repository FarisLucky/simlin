<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Http\Requests\StoreLinenDetailRequest;
use App\Models\LinenDetail;
use App\Models\MLinenUnit;
use Exception;
use Illuminate\Http\Request;

class TransLinenDetailController extends Controller
{
    use ApiResponse;

    public function store(StoreLinenDetailRequest $request)
    {

        try {
            $user = auth()->user();
            $input = $request->validated();
            $linenDetail = LinenDetail::where(function ($query) use ($input) {
                $query->where('kode_linen_unit', $input['kode'])
                    ->where('kode_daftar', $input['kode_daftar']);
            })->first();

            if (!is_null($linenDetail)) {
                throw new Exception('linen sudah ditambahkan');
            }

            $linen = MLinenUnit::where('kode', $input['kode'])->first();
            if (is_null($linen)) {
                throw new Exception('Master linen tidak ada');
            }

            // $sisa = $linen->jml;
            // if ($request->jml > $sisa) {
            //     throw new Exception("Gagal! Jumlah linen Total: {$linen->jml}, Steril: {$linen->steril}, Kotor: {$linen->kotor}");
            // }

            $input['kode_linen'] = $linen->kode_linen;
            $input['kode_linen_unit'] = $linen->kode;
            $input['status'] = LinenDetail::PROGRESS;
            $input['created_by'] = $user->getAuthIdentifier();
            $input['updated_by'] = $user->getAuthIdentifier();
            $input['updated_by_name'] = $user->username;

            $store = LinenDetail::create($input);

            return $this->okApiResponse($store, 'Berhasil disimpan');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function showByKodeDaftar($kode)
    {
        try {

            $linen = LinenDetail::with([
                'daftar' => function($query){
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

            $linen = LinenDetail::where('id', $id)->firstOrFail();
            $linen->delete();

            return $this->noContentApiResponse('');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function updateQty(Request $request, $id)
    {
        try {

            $linen = LinenDetail::where('id', $id)->first();
            if(is_null($linen)){
                throw new Exception('Linen Sudah Kosong/Dihapus');
            }

            if($request->jml > $linen->jml) {
                throw new Exception("Qty Max. {$linen->jml}");
            }

            if($request->jenis === 'jml') {
                $linen->jml = $request->jml;

            } else if($request->jenis === 'jml_terima') {
                $linen->jml_terima = $request->jml;

            } else if($request->jenis === 'jml_kembali') {
                $linen->jml_kembali = $request->jml;

            } else if($request->jenis === 'berat') {
                $linen->berat = str_replace(',','.',$request->berat);

            } else if ($request->jenis === 'jml_akhir') {
                $linen->jml_akhir = $request->jml;

            }

            $linen->save();

            return $this->okApiResponse($linen,'Berhasil Update');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }
}
