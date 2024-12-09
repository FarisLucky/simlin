<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use Ramsey\Uuid\Uuid;

class Linen extends Model
{
    use Searchable;

    const PENGAJUAN = 0;
    const SELESAI = 1;

    protected $table = "linen";

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nota',
        'kode_daftar',
        'nama',
        'jml',
        'berat',
        'selesai',
        'kembali',
        'status',
        'kd_unit',
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
            'nota',
            'kode_daftar',
            'nama',
            'jml',
            'berat',
            'selesai',
            'kembali',
            'status',
            'kd_unit',
            'created_by',
            'updated_by',
            'updated_by_name'
        );
    }

    public function toSearchableArray()
    {
        $array = [
            'nota' => $this->nota,
            'kode_daftar' => $this->kode_daftar,
            'nama' => $this->nama,
        ];

        return $array;
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

        static::deleted(function($linen){
            if(count($linen->detail) > 0) {
                $linen->detail->each->delete();
            }
        });
    }

    public function scopeByKodeDaftar($query, $kode)
    {
        return $query->when(!is_null($kode), function ($query) use ($kode) {
            return $query->where('kode_daftar', $kode);
        });
    }

    public function mUnit(): BelongsTo
    {
        return $this->belongsTo(MUnit::class, 'kd_unit', 'kode');
    }

    public function daftar(): BelongsTo
    {
        return $this->belongsTo(Daftar::class, 'kode_daftar', 'kode');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(LinenDetail::class, 'nota_linen', 'nota');
    }
}
