<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

class PinjamAlatDetail extends Model
{
    protected $table = "pinjam_alat_detail";

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nota_pinjam',
        'kode_daftar',
        'kode_alat',
        'nama',
        'jml',
        'jml_kembali',
        'pinjam',
        'kembali',
        'status',
        'created_by',
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
            'nota_pinjam',
            'kode_daftar',
            'kode_alat',
            'nama',
            'jml',
            'jml_kembali',
            'pinjam',
            'kembali',
            'status',
            'created_by',
            'updated_by',
            'updated_by_name',
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Uuid::uuid4();
            }
        });
    }

    public function daftar(): BelongsTo
    {
        return $this->belongsTo(Daftar::class, 'kode_daftar', 'kode');
    }

    public function mAlat(): BelongsTo
    {
        return $this->belongsTo(MAlat::class, 'kode_alat', 'kode');
    }
}
