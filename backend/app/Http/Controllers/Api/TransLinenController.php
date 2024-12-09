<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Http\Requests\StoreLinenRequest;
use App\Models\Daftar;
use App\Models\Linen;
use App\Models\MLinenUnit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransLinenController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {

            $search = request('search');
            $sortBy = request('sortBy');
            $sortType = request('sortType');
            $perPage = request('rowsPerPage');
            $page = request('page');

            $linens = Linen::search($search)
            ->whenSort($sortBy, $sortType)
            ->paginate($perPage);

            return $this->okApiResponse(
                $linens,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getTraceAsString());
        }
    }

    public function data()
    {
        try {

            $search = request('search');
            $linens = Linen::whenSearch($search)->selectIdx()->get();

            return $this->okApiResponse(
                $linens,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function store(StoreLinenRequest $request)
    {

        try {
            $input = $request->validated();

            $linen = MLinenUnit::where('kode', $input['kode'])->first();
            if(is_null($linen)){
                throw new Exception('Master linen tidak ada');
            }

            throw new Exception('BELOM');

            // $payload = [

            // ];

            return $this->okApiResponse($linen, 'Berhasil disimpan');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function show($id)
    {
        try {

            $linen = Linen::find($id);

            return $this->okApiResponse($linen, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function showBy()
    {
        try {
            $kodeDaftar = request('kode_daftar');
            $jenis = request('jenis');

            $linen = Daftar::whenWithJenis($jenis)
            ->byKodeDaftar( $kodeDaftar)
            ->first();

            return $this->okApiResponse($linen, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }


    public function update(Request $request, $kode)
    {
        try {

            $linen = Linen::with(['daftar'])->where('kode',$kode)->first();
            $linen->update(['berat' => $request->berat]);
            Log::info($request->all());
            $linen->daftar->forceFill(['ket' => $request->ket])->save();

            return $this->okApiResponse($linen, 'Berhasil diperbarui');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function destroy($kode)
    {
        try {

            $linen = Linen::where('kode',$kode)->first();
            $linen->delete();

            return $this->noContentApiResponse('');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }
}
