<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Http\Requests\StoreMUnitRequest;
use App\Http\Requests\UpdateMUnitRequest;
use App\Models\MUnit;
use Illuminate\Support\Facades\Log;

class MUnitController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {

            $search = request('search');
            $sortBy = request('sortBy');
            $sortType = request('sortType');

            $units = MUnit::whenSearch($search)
            ->whenSort($sortBy,$sortType)
            ->get();

            return $this->okApiResponse(
                $units,
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
            $units = MUnit::whenSearch($search)->selectIdx()->get();

            return $this->okApiResponse(
                $units,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function store(StoreMUnitRequest $request)
    {

        try {
            $input = $request->validated();

            $unit = MUnit::create($input);

            return $this->okApiResponse($unit, 'Berhasil disimpan');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function show($id)
    {
        try {

            $unit = MUnit::find($id);

            return $this->okApiResponse($unit, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function update(UpdateMUnitRequest $request, $kode)
    {
        try {

            $input = $request->validated();
            $unit = MUnit::where('kode',$kode)->first();

            $unit->update($input);

            return $this->okApiResponse($unit, 'Berhasil diperbarui');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function destroy($kode)
    {
        try {

            $unit = MUnit::where('kode',$kode)->first();
            $unit->delete();

            return $this->noContentApiResponse('');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }
}
