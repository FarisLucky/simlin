<?php

namespace App\Models;

use App\Models\Contracts\SearchInterface;
use App\Models\Contracts\SortInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;

class Daftar extends Model implements SearchInterface, SortInterface
{
    use Searchable;

    const LINEN = 'LINEN';
    const ALAT = 'ALAT';
    const NOTA = 0;
    const PENGAJUAN = 1;
    const TERIMA = 2;
    const DIKEMBALIKAN = 3;
    const SELESAI = 4;

    protected $table = "daftar";
    public $asYouType = true;

    protected $fillable = [
        'id',
        'kode',
        'jml',
        'jenis',
        'pengajuan',
        'terima',
        'kembalikan',
        'selesai',
        'ket',
        'kd_unit',
        'penerima',
        'status',
        'created_by',
        'created_by_name',
        'updated_by',
        'updated_by_name',
        'updated_by',
        'updated_by_name',
    ];

    protected $appends = ['jenis_cast', 'pengajuan_cast', 'terima_cast', 'kembalikan_cast', 'status_cast', 'selesai_cast', 'created_at_cast'];

    public $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeSelectIdx($query)
    {
        return $query->select(
            'id',
            'kode',
            'jml',
            'jenis',
            'pengajuan',
            'terima',
            'kembalikan',
            'selesai',
            'ket',
            'kd_unit',
            'status',
            'created_by',
            'created_by_name',
            'updated_by',
            'updated_by_name',
            'updated_by',
            'updated_by_name'
        );
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'kode' => $this->kode,
            'jml' => $this->jml,
            'jenis' => $this->jenis,
            'linen_detail' => $this->linenDetail->pluck('nama')->toArray(),
        ];
    }

    public function searchableAs()
    {
        return 'daftar_index';
    }

    public function scopeWhenSearch($query, $search)
    {
        return $query->when(!is_null($search), function ($query) use ($search) {
            return $query->where('kode', 'LIKE', "%{$search}%")->orWhere('nama', 'LIKE', "%{$search}%");
        });
    }

    public function scopeWhenJenis($query, $jenis)
    {
        return $query->when(!is_null($jenis), function ($query) use ($jenis) {
            if ($jenis === self::LINEN) {
                return $query->with('linen')->where('jenis', 'LIKE', "%{$jenis}%");
            } else if ($jenis === self::ALAT) {
                return $query->with('pinjamAlat')->where('jenis', 'LIKE', "%{$jenis}%");
            }

            return $query;
        });
    }

    public function scopeWhenSort($query, $keyBy, $keyType)
    {
        return $query->when(!is_null($keyBy) && !is_null($keyType), function ($query) use ($keyBy, $keyType) {
            return $query->orderBy($keyBy, $keyType);
        });
    }

    public function scopeWhenGrantForUnit($query, $user)
    {
        return $query->when($user->role === User::UNIT, function ($query) use ($user) {
            return $query->where('kd_unit', $user->kd_unit);
        });
    }

    public function scopeByKodeDaftar($query, $kode)
    {
        return $query->when(!is_null($kode), function ($query) use ($kode) {
            return $query->where('kode', $kode);
        });
    }

    public function getCreatedAtCastAttribute()
    {
        return !is_null($this->created_at) ? Carbon::make($this->created_at)->format('d-m-Y H:i') : '-';
    }

    public function getPengajuanCastAttribute()
    {
        return !is_null($this->pengajuan) ? Carbon::make($this->pengajuan)->format('d-m-Y H:i') : '-';
    }

    public function getTerimaCastAttribute()
    {
        return !is_null($this->terima) ? Carbon::make($this->terima)->format('d-m-Y H:i') : '-';
    }

    public function getKembalikanCastAttribute()
    {
        return !is_null($this->kembalikan) ? Carbon::make($this->kembalikan)->format('d-m-Y H:i') : '-';
    }

    public function getSelesaiCastAttribute()
    {
        return !is_null($this->selesai) ? Carbon::make($this->selesai)->format('d-m-Y H:i') : '-';
    }

    public function getJenisCastAttribute()
    {
        return !is_null($this->jenis) && $this->jenis === self::LINEN ? self::LINEN : self::ALAT;
    }

    public function getStatusCastAttribute()
    {
        $status = '';
        if ($this->jenis === Daftar::LINEN) {
            switch ($this->status) {
                case self::NOTA:
                    $status = 'NOTA';
                    break;
                case self::PENGAJUAN:
                    $status = 'PENGAJUAN';
                    break;
                case self::TERIMA:
                    $status = 'DITERIMA CSSD';
                    break;
                case self::DIKEMBALIKAN:
                    $status = 'DISTRIBUSI RUANGAN';
                    break;
                case self::SELESAI:
                    $status = 'KONFIRMASI RUANGAN';
                    break;
            }
        } else if ($this->jenis === Daftar::ALAT) {
            switch ($this->status) {
                case self::NOTA:
                    $status = 'NOTA';
                    break;
                case self::PENGAJUAN:
                    $status = 'PENGAJUAN';
                    break;
                case self::TERIMA:
                    $status = 'DITERIMA CSSD';
                    break;
                case self::DIKEMBALIKAN:
                    $status = 'DISTRIBUSI RUANGAN';
                    break;
                case self::SELESAI:
                    $status = 'SELESAI';
                    break;
            }
        }

        return $status;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($daftar) {
            $daftar->updated_by = auth()->user()->getAuthIdentifier();
        });

        static::deleted(function ($daftar) {
            if ($daftar->jenis === self::LINEN && !is_null($daftar->linen)) {
                // if (count($daftar->linen) > 0) {
                //     foreach ($daftar->linen as $linen) {
                $daftar->linen->delete();
                //     }
                // }
            } else if ($daftar->jenis === self::ALAT && !is_null($daftar->pinjamAlat)) {
                // if (count($daftar->pinjamAlat) > 0) {
                //     foreach ($daftar->pinjamAlat as $alat) {
                $daftar->pinjamAlat->delete();
                //     }
                // }
            }
        });
    }

    public function linen(): HasOne
    {
        return $this->hasOne(Linen::class, 'kode_daftar', 'kode');
    }

    public function linenDetail(): HasMany
    {
        return $this->hasMany(LinenDetail::class, 'kode_daftar', 'kode');
    }

    public function pinjamAlat(): HasMany
    {
        return $this->hasMany(PinjamAlat::class, 'kode_daftar', 'kode');
    }

    public function pinjamAlatDetail(): HasMany
    {
        return $this->hasMany(PinjamAlatDetail::class, 'kode_daftar', 'kode');
    }
    public function unit(): BelongsTo
    {
        return $this->belongsto(MUnit::class, 'kd_unit', 'kode');
    }
    public function mutu(): HasOne
    {
        return $this->hasOne(Mutu::class, 'kode_daftar', 'kode');
    }
}
