<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\MBundle;
use Illuminate\Http\Request;

class MBundleController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {

            $search = request('search');
            $sortBy = request('sortBy');
            $sortType = request('sortType');

            $mBundles = MBundle::with([
                'kategori' => function($query){
                    $query->select('id','nama');
                },
                'mBundleDetail' => function($query){
                    $query->select('id','id_bundle','alat','jml');
                }
            ])->whenSearch($search)
                ->whenSort($sortBy, $sortType)
                ->latest()
                ->get();

            return $this->okApiResponse(
                $mBundles,
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
            $idKategori = request('kategori');

            $mBundles = MBundle::selectIdx()
            ->when(!is_null($search), function($query) use($search){
                $query->where('nama', 'LIKE', "%{$search}%");
            })
            ->when(!is_null($idKategori), function($query) use($idKategori){
                $query->where('id_kategori', $idKategori);
            })
            ->get();

            return $this->okApiResponse(
                $mBundles,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function store(Request $request)
    {

        try {
            $mBundle = MBundle::create([
                'nama' => $request->nama,
                'id_kategori' => $request->id_kategori,
            ]);

            return $this->okApiResponse($mBundle, 'Berhasil disimpan');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function show($kode)
    {
        try {

            $mBundle = MBundle::find($kode);

            return $this->okApiResponse($mBundle, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $input = $request->validated();

            $mBundle = MBundle::find($id);
            $mBundle->update([
                'nama' => $request->nama,
                'id_kategori' => $request->id_kategori,
            ]);

            return $this->okApiResponse($mBundle, 'Berhasil diperbarui');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {

            $mBundle = MBundle::find($id);
            $mBundle->delete();

            return $this->noContentApiResponse('');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }
}
