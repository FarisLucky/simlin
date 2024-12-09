<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Daftar;
use App\Models\LinenDetail;
use App\Models\Mutu;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MutuController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {

            $search = request('search');
            // $sortBy = request('sortBy');
            // $sortType = request('sortType');
            $perPage = request('rowsPerPage');
            // $page = request('page');

            $mutu = Mutu::whenSearch($search)->paginate($perPage);

            return $this->okApiResponse(
                $mutu,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $daftar = Daftar::where('kode', $request->kode)->first();

            $payload = [
                'kode_daftar' => $daftar->kode,
                'tgl_daftar' => Carbon::make($daftar->pengajuan)->format('Y-m-d'),
                'tdk_noda' => $request->tdk_noda,
                'tdk_bau' => $request->tdk_bau,
                'tdk_pudar' => $request->tdk_pudar,
                'tdk_rapi' => $request->tdk_rapi,
                'jml_rusak' => $request->jml_rusak,
                'ttl' => $request->ttl,
            ];

            $mutu = Mutu::create($payload);

            return $this->okApiResponse(
                $mutu,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function show($id)
    {
        //
    }

    public function showByDaftar($kode)
    {
        try {
            $mutu = Mutu::where('kode_daftar', $kode)
                ->selectIdx()
                ->first();
            if (is_null($mutu)) {
                $details = LinenDetail::where('kode_daftar', $kode)->get();
                $sum = 0;
                foreach ($details as $detail) {
                    $sum += $detail->jml_akhir;
                }

                $payload = [
                    'kode_daftar' => $kode,
                    'tgl_daftar' => now(),
                    'jml_rusak' => 0,
                    'ttl' => $sum,
                ];

                $mutu = Mutu::create($payload);
            }

            return $this->okApiResponse(
                $mutu,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $mutu = Mutu::find($id);

            $payload = [
                'tdk_noda' => $request->tdk_noda,
                'tdk_bau' => $request->tdk_bau,
                'tdk_pudar' => $request->tdk_pudar,
                'tdk_rapi' => $request->tdk_rapi,
                'tdk_kualitas' => $request->tdk_kualitas,
                'jml_rusak' => $request->jml_rusak,
                // 'ttl' => $request->ttl,
            ];

            $mutu->update($payload);

            return $this->okApiResponse(
                $mutu,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function destroy($id)
    {
        //
    }
}
