<?php

namespace App\Models;

use App\Models\Contracts\SearchInterface;
use App\Models\Contracts\SortInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MLinenUnit extends Model implements SearchInterface, SortInterface
{
    const NONAKTIF = 0;

    const AKTIF = 1;

    protected $table = "m_linen_unit";

    protected $fillable = [
        'id',
        'kode',
        'kode_linen',
        'kode_unit',
        'nama',
        'terpakai',
        'bersih',
        'kotor',
        'status',
        'updated_by',
    ];

    protected $appends = ['status_desc'];

    public $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeSelectIdx($query)
    {
        return $query->select(
            'id',
            'kode',
            'kode_linen',
            'kode_unit',
            'nama',
            'terpakai',
            'bersih',
            'kotor',
            'status',
            'updated_by'
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($unit) {
            $unit->updated_by = auth()->user()->getAuthIdentifier();
        });
    }

    public function scopeWhenSearch($query, $search)
    {
        return $query->when(!is_null($search), function($query) use($search){
            return $query->where('kode','LIKE', "%{$search}%")->orWhere('nama','LIKE', "%{$search}%");
        });
    }

    public function scopeWhenByUnit($query, $unit)
    {
        return $query->when(!is_null($unit), function($query) use($unit){
            return $query->where('kode_unit', "{$unit}");
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

    public function mLinen(): BelongsTo
    {
        return $this->belongsTo(MLinen::class, 'kode_linen');
    }

    public function mUnit(): BelongsTo
    {
        return $this->belongsTo(MUnit::class, 'kode_unit');
    }
}
