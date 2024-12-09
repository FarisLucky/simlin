<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Http\Requests\StoreMBundleDetailRequest;
use App\Http\Requests\UpdateMBundleDetailRequest;
use App\Models\MAlat;
use App\Models\MBundleDetail;

class MBundleDetailController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {

            $search = request('search');
            $sortBy = request('sortBy');
            $sortType = request('sortType');
            // $perPage = request('rowsPerPage');

            $mBundleDetails = MBundleDetail::whenSearch($search)
                ->whenSort($sortBy, $sortType)
                ->get();

            return $this->okApiResponse(
                $mBundleDetails,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function data()
    {
        try {

            $idBundle = request('id_bundle');

            $mBundleDetails = MBundleDetail::selectIdx()
            ->when(!is_null($idBundle), function($query) use ($idBundle){
                $query->where('id_bundle', $idBundle);
            })
            ->get();

            return $this->okApiResponse(
                $mBundleDetails,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function store(StoreMBundleDetailRequest $request)
    {

        try {
            $input = $request->validated();

            $mAlat = MAlat::where('kode', $input['kode_alat'])->first(['nama']);
            $input['alat'] = $mAlat->nama;

            $mBundleDetail = MBundleDetail::create($input);

            return $this->okApiResponse($mBundleDetail, 'Berhasil disimpan');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function show($kode)
    {
        try {
            $mBundleDetail = MBundleDetail::find($kode);

            return $this->okApiResponse($mBundleDetail, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function update(UpdateMBundleDetailRequest $request, $kode)
    {
        try {

            $input = $request->validated();

            $mBundleDetail = MBundleDetail::where('kode', $kode)->first();
            $mBundleDetail->update($input);

            return $this->okApiResponse($mBundleDetail, 'Berhasil diperbarui');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {

            $mBundleDetail = MBundleDetail::find($id);
            $mBundleDetail->delete();

            return $this->noContentApiResponse('');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }
}
