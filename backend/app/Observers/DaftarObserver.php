<?php

namespace App\Observers;

use App\Models\Daftar;
use App\Models\Linen;
use App\Models\PinjamAlat;
use Illuminate\Support\Facades\DB;

class DaftarObserver
{
    /**
     * Handle the Daftar "created" event.
     *
     * @param  \App\Models\Daftar  $daftar
     * @return void
     */
    public function created(Daftar $daftar)
    {
        //
    }

    /**
     * Handle the Daftar "updated" event.
     *
     * @param  \App\Models\Daftar  $daftar
     * @return void
     */
    public function updating(Daftar $daftar)
    {
        $user = auth()->user();
        $daftar->updated_by = $user->id;
        $daftar->updated_by_name = $user->username;
    }

    /**
     * Handle the Daftar "deleted" event.
     *
     * @param  \App\Models\Daftar  $daftar
     * @return void
     */
    public function deleted(Daftar $daftar)
    {
        DB::beginTransaction();

        switch ($daftar->jenis) {
            case Daftar::LINEN:
                $linen = Linen::with('detail')
                    ->where('kode_daftar', $daftar->kode)
                    ->first();

                if (!is_null($linen) && $linen->detail->isNotEmpty()) {
                    foreach ($linen->detail as $item) {
                        $item->delete();
                    }
                }
                $linen->delete();

                break;
            case Daftar::ALAT:
                $alat = PinjamAlat::with('detail')
                    ->where('kode_daftar', $daftar->kode)
                    ->first();

                if (!is_null($alat) && $alat->detail->isNotEmpty()) {
                    foreach ($alat->detail as $item) {
                        $item->delete();
                    }
                }
                $alat->delete();

                break;
        }

        DB::commit();
    }
}
