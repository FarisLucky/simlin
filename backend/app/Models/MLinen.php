<?php

namespace App\Models;

use App\Models\Contracts\SearchInterface;
use App\Models\Contracts\SortInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class MLinen extends Model implements SearchInterface, SortInterface
{
    protected $table = "m_linen";

    protected $primaryKey = 'kode';

    protected $keyType = 'string';

    protected $fillable = [
        'kode',
        'nama',
        'updated_by',
    ];

    public $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeSelectIdx($query)
    {
        return $query->select('kode', 'nama');
    }

    public function scopeWhenSearch($query, $search)
    {
        return $query->when(!is_null($search), function($query) use($search){
            return $query->where('kode','LIKE', "%{$search}%")->orWhere('nama','LIKE', "%{$search}%");
        });
    }

    public function scopeWhenSort($query, $keyBy, $keyType)
    {
        return $query->when(!is_null($keyBy) && !is_null($keyType), function($query) use($keyBy, $keyType){
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

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($unit) {
            $unit->updated_by = auth()->user()->getAuthIdentifier();
        });
    }
}
