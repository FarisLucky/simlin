<?php

namespace App\Services;

use App\Models\MBundle;
use App\Models\MBundleDetail;
use App\Models\PinjamAlat;
use App\Models\PinjamAlatDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use Str;

class PinjamService
{
    public static function updateDetail(Collection $pinjamAlat)
    {
        foreach ($pinjamAlat as $pinjam) {
            if (!is_null($pinjam->id_bundle)) {
                $pinjamDetailPayload = [];
                if (!is_null($pinjam->id_kategori) && $pinjam->id_bundle) {
                    $bundleDetails = MBundleDetail::where('id_bundle', $pinjam->id_bundle)->get();
                    // Log::info($bundleDetails);
                    foreach ($bundleDetails as $detail) {
                        $pinjamDetailPayload[] = [
                            'id' => Uuid::uuid4(),
                            'nota_pinjam' => $pinjam->nota,
                            'kode_daftar' => $pinjam->kode_daftar,
                            'kode_alat' => optional($detail)->kode_alat,
                            'nama' => $detail->alat,
                            'jml' => $detail->jml,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    PinjamAlatDetail::insert($pinjamDetailPayload);
                    /**
                     * Master Bundle
                     */
                    $mBundle = MBundle::where('id', $pinjam->id_bundle)->first();
                    $mBundle->dipinjam = $pinjam->kd_unit;
                    $mBundle->dipinjam_at = now();
                    $mBundle->save();
                }
            }
        }
    }

    public static function genNota()
    {
        // Ambil tahun dan bulan saat ini
        $currentYearMonth = Carbon::now()->format('Ymd');

        // Ambil kode terakhir berdasarkan tahun dan bulan
        $lastOrder = PinjamAlat::where('nota', 'LIKE', $currentYearMonth . '%')
            ->orderByDesc('nota')
            ->first();

        // Tentukan nomor urut
        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->nota, -4); // Ambil 4 digit terakhir
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1; // Jika belum ada data, mulai dari 1
        }

        // Format menjadi 4 digit
        $newCode = $currentYearMonth . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        return $newCode;
    }
}
