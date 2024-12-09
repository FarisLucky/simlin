<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Http\Requests\StoreMLinenRequest;
use App\Http\Requests\UpdateMLinenRequest;
use App\Models\MLinen;
use App\Services\MLinenService;

class MLinenController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {

            $search = request('search');
            $sortBy = request('sortBy');
            $sortType = request('sortType');

            $linens = MLinen::whenSearch($search)
            ->whenSort($sortBy, $sortType)
            ->get();

            return $this->okApiResponse(
                $linens,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function data()
    {
        try {

            $search = request('search');
            $linens = MLinen::whenSearch($search)->selectIdx()->get();

            return $this->okApiResponse(
                $linens,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function store(StoreMLinenRequest $request)
    {

        try {
            $input = $request->validated();
            $input['kode'] = MLinenService::genKode();

            $linen = MLinen::create($input);

            return $this->okApiResponse($linen, 'Berhasil disimpan');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function show($id)
    {
        try {

            $linen = MLinen::find($id);

            return $this->okApiResponse($linen, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function update(UpdateMLinenRequest $request, $kode)
    {
        try {

            $input = $request->validated();
            $linen = MLinen::where('kode',$kode)->first();

            $linen->update($input);

            return $this->okApiResponse($linen, 'Berhasil diperbarui');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function destroy($kode)
    {
        try {

            $linen = MLinen::where('kode',$kode)->first();
            $linen->delete();

            return $this->noContentApiResponse('');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }
}
