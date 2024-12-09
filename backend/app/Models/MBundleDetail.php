<?php

namespace App\Models;

use App\Models\Contracts\SearchInterface;
use App\Models\Contracts\SortInterface;
use Illuminate\Database\Eloquent\Model;

class MBundleDetail extends Model implements SearchInterface, SortInterface
{
    const NONAKTIF = 0;
    const AKTIF = 1;

    protected $table = "m_bundle_detail";

    public $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['status_desc'];

    protected $fillable = [
        'id',
        'id_bundle',
        'kode_alat',
        'alat',
        'jml',
    ];

    public function scopeSelectIdx($query)
    {
        return $query->select(
            'id',
            'kode_alat',
            'alat',
            'jml',
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

    public function getStatusDescAttribute()
    {
        return $this->status === self::NONAKTIF ? 'NONAKTIF' : 'AKTIF';
    }
}
