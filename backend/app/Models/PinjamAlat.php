<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;

class PinjamAlat extends Model
{

    const PENGAJUAN = 0;
    const SELESAI = 1;

    protected $table = "pinjam_alat";

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nota',
        'kode_daftar',
        'id_bundle',
        'id_kategori',
        'nama',
        'bundle',
        'pinjam',
        'kembali',
        'status',
        'kd_unit',
        'created_by',
        'created_by_name',
        'updated_by',
        'updated_by_name',
    ];

    public $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeSelectIdx($query)
    {
        return $query->select(
            'id',
            'nota',
            'kode_daftar',
            'id_bundle',
            'id_kategori',
            'nama',
            'bundle',
            'pinjam',
            'kembali',
            'status',
            'kd_unit',
            'created_by',
            'created_by_name',
            'updated_by',
            'updated_by_name',
        );
    }

    protected $appends = ['pinjam_cast'];

    public function getPinjamCastAttribute()
    {
        return !is_null($this->pinjam) ? Carbon::make($this->pinjam)->format('d-m-Y H:i') : $this->pinjam;
    }


    // Boot function to auto-generate UUID when creating a new model instance
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Uuid::uuid4();
            }
        });

        static::deleted(function($pinjam){
            if(count($pinjam->detail) > 0) {
                $pinjam->detail->each->delete();
            }
        });
    }

    public function mUnit(): BelongsTo
    {
        return $this->belongsTo(MUnit::class, 'kd_unit', 'kode');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(PinjamAlatDetail::class, 'nota_pinjam', 'nota');
    }

    public function bundleDetail(): HasMany
    {
        return $this->hasMany(MBundleDetail::class, 'id_bundle', 'id_bundle');
    }

    public function catatan(): HasOne
    {
        return $this->hasOne(Catatan::class, 'id_relation', 'id');
    }
}
