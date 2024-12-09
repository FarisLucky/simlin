<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class MKategori extends Model
{
    protected $table = "m_kategori";

    protected $fillable = [
        'nama',
    ];

    public $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeSelectIdx($query)
    {
        return $query->select('id', 'nama');
    }

    public function scopeWhenSearch($query, $search)
    {
        return $query->when(!is_null($search), function ($query) use ($search) {
            return $query->where('nama', 'LIKE', "%{$search}%");
        });
    }

    public function scopeWhenSort($query, $keyBy, $keyType)
    {
        return $query->when(!is_null($keyBy) && !is_null($keyType), function ($query) use ($keyBy, $keyType) {
            return $query->orderBy($keyBy, $keyType);
        });
    }

    public function getCreatedAtAttribute($val)
    {
        return !is_null($val) ? Carbon::make($val)->format('d-m-Y H:i') : '';
    }

    public function getUpdatedAtAttribute($val)
    {
        return !is_null($val) ? Carbon::make($val)->format('d-m-Y H:i') : '';
    }
}
