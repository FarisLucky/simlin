<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Http\Requests\StoreMAlatRequest;
use App\Http\Requests\UpdateMAlatRequest;
use App\Models\MAlat;
use App\Services\MAlatService;

class MAlatController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {

            $search = request('search');
            $sortBy = request('sortBy');
            $sortType = request('sortType');

            $mAlats = MAlat::whenSearch($search)
                ->whenSort($sortBy, $sortType)
                ->get();

            return $this->okApiResponse(
                $mAlats,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function data()
    {
        try {

            $mAlats = MAlat::selectIdx()
                ->get();

            return $this->okApiResponse(
                $mAlats,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function store(StoreMAlatRequest $request)
    {

        try {
            $input = $request->validated();
            $input['kode'] = MAlatService::genKode();

            $mAlat = MAlat::create($input);

            return $this->okApiResponse($mAlat, 'Berhasil disimpan');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function show($kode)
    {
        try {

            $mAlat = MAlat::find($kode);

            return $this->okApiResponse($mAlat, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function update(UpdateMAlatRequest $request, $kode)
    {
        try {
            $input = $request->validated();

            $mAlat = MAlat::where('kode', $kode)->first();
            unset($input['kode']);
            // if($input['jml'] < $mAlat->sisa) {
            //     throw Exception('Sisa masih lebih banyak');
            // }
            $mAlat->update($input);

            return $this->okApiResponse($mAlat, 'Berhasil diperbarui');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function destroy($kode)
    {
        try {

            $mAlat = MAlat::where('kode', $kode)->first();
            $mAlat->delete();

            return $this->noContentApiResponse('');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }
}
