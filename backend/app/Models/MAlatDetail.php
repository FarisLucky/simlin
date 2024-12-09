<?php

namespace App\Models;

use App\Models\Contracts\SearchInterface;
use App\Models\Contracts\SortInterface;
use Illuminate\Database\Eloquent\Model;

class MAlatDetail extends Model implements SearchInterface, SortInterface
{
    const NONAKTIF = 0;
    const AKTIF = 1;

    protected $table = "m_alat_detail";
    protected $primaryKey = 'kode';
    protected $keyType = 'string';

    public $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['status_desc'];

    protected $fillable = [
        'kode',
        'kode_alat',
        'nama',
        'id_kategori',
        'kategori',
        'jml',
        'steril',
        'kondisi',
        'status',
        'updated_by',
    ];

    public function scopeSelectIdx($query)
    {
        return $query->select(
            'kode',
            'kode_alat',
            'nama',
            'id_kategori',
            'kategori',
            'jml',
            'steril',
            'status',
            'updated_by'
        );
    }

    public function scopeWhenSearch($query, $search)
    {
        return $query->when(!is_null($search), function($query) use($search){
            return $query->where('kode', "%{$search}%")->orWhere('nama','LIKE', "%{$search}%");
        });
    }

    public function scopeWhenSort($query, $keyBy, $keyType)
    {
        return $query->when(!is_null($keyBy) && !is_null($keyType), function($query) use($keyBy, $keyType){
            return $query->orderBy($keyBy, $keyType);
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($unit) {
            $unit->updated_by = auth()->user()->getAuthIdentifier();
        });
    }

    public function getStatusDescAttribute()
    {
        return $this->status === self::NONAKTIF ? 'NONAKTIF' : 'AKTIF';
    }
}
