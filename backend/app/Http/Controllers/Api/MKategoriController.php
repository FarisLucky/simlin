<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Http\Requests\StoreMAlatRequest;
use App\Http\Requests\UpdateMAlatRequest;
use App\Models\MKategori;

class MKategoriController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {

            $search = request('search');
            $sortBy = request('sortBy');
            $sortType = request('sortType');

            $mKategories = MKategori::whenSearch($search)
                ->whenSort($sortBy, $sortType)
                ->get();

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

            $mKategories = MKategori::selectIdx()->get();

            return $this->okApiResponse(
                $mKategories,
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
            $mKategori = MKategori::create($input);

            return $this->okApiResponse($mKategori, 'Berhasil disimpan');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function show($id)
    {
        try {

            $mKategori = MKategori::find($id);

            return $this->okApiResponse($mKategori, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function update(UpdateMAlatRequest $request, $id)
    {
        try {
            $input = $request->validated();

            $mKategori = MKategori::where('id', $id)->first();
            $mKategori->update($input);

            return $this->okApiResponse($mKategori, 'Berhasil diperbarui');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {

            $mKategori = MKategori::where('id', $id)->first();
            $mKategori->delete();

            return $this->noContentApiResponse('');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }
}
