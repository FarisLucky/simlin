<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Http\Requests\StoreMLinenUnitRequest;
use App\Http\Requests\UpdateMLinenUnitRequest;
use App\Models\MLinen;
use App\Models\MLinenUnit;
use App\Services\MLinenUnitService;
use Illuminate\Support\Facades\Log;

class MLinenUnitController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {

            $search = request('search');
            $sortBy = request('sortBy');
            $sortType = request('sortType');

            $linens = MLinenUnit::with([
                'MUnit' => function ($query) {
                    $query->select('kode', 'nama');
                }
            ])
                ->whenSearch($search)
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
            $user = auth()->user();
            $linens = MLinenUnit::whenSearch($search)
                ->whenByUnit($user->kd_unit)
                ->selectIdx()
                ->get();

            return $this->okApiResponse(
                $linens,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function store(StoreMLinenUnitRequest $request)
    {

        try {
            $input = $request->validated();

            $linen = MLinen::where('kode', $input['kode_linen'])->first(['nama']);

            $mLinenUnitService = new MLinenUnitService();
            $exists = $mLinenUnitService->linenExists($request->only('kode_linen', 'kode_unit'));

            if (!is_null($exists)) {
                throw new \Exception('UNIT SUDAH MEMILIKI LINEN ' . $linen->nama);
            }

            $input['kode'] = $mLinenUnitService
                ->setKodeUnit($input['kode_unit'])
                ->genKode();
            $input['nama'] = $linen->nama;

            $linen = MLinenUnit::create($input);

            return $this->okApiResponse($linen, 'Berhasil disimpan');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function show($id)
    {
        try {

            $linen = MLinenUnit::find($id);

            return $this->okApiResponse($linen, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function update(UpdateMLinenUnitRequest $request, $kode)
    {
        try {
            $input = $request->validated();

            $linen = MLinenUnit::where('kode', $kode)->first();
            unset($input['kode']);

            $linen->update($input);

            return $this->okApiResponse($linen, 'Berhasil diperbarui');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function destroy($kode)
    {
        try {

            $linen = MLinenUnit::where('kode', $kode)->first();
            $linen->delete();

            return $this->noContentApiResponse('');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }
}
