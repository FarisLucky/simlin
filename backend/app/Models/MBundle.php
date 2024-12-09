<?php

namespace App\Models;

use App\Models\Contracts\SearchInterface;
use App\Models\Contracts\SortInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class MBundle extends Model implements SearchInterface, SortInterface
{
    protected $table = "m_bundle";

    protected $fillable = [
        'id_kategori',
        'nama',
    ];

    public $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeSelectIdx($query)
    {
        return $query->select('id', 'id_kategori', 'nama');
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

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(MKategori::class, 'id_kategori', 'id');
    }

    public function mBundleDetail(): HasMany
    {
        return $this->hasMany(MBundleDetail::class, 'id_bundle', 'id');
    }
}
