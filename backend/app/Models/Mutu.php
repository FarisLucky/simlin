<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mutu extends Model
{
    protected $table = "mutu";

    protected $fillable = [
        'id',
        'tgl_daftar',
        'kode_daftar',
        'tdk_noda',
        'tdk_bau',
        'tdk_pudar',
        'tdk_rapi',
        'tdk_kualitas',
        'jml_rusak',
        'ttl',
    ];

    public $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeSelectIdx($query)
    {
        return $query->select(
            'id',
            'tgl_daftar',
            'kode_daftar',
            'tdk_noda',
            'tdk_bau',
            'tdk_pudar',
            'tdk_rapi',
            'tdk_kualitas',
            'jml_rusak',
            'ttl'
        );
    }

    public function scopeWhenSearch($query, $search)
    {
        return $query->when(!is_null($search), function ($query) use ($search) {
            return $query->where('kode_daftar', 'LIKE', "%{$search}%")->orWhere('tgl_daftar', 'LIKE', "%{$search}%");
        });
    }

    public function daftar(): BelongsTo
    {
        return $this->belongsTo(Daftar::class, 'kode_daftar', 'kode');
    }
}
