<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;
use Ramsey\Uuid\Uuid;

class LinenDetail extends Model
{
    use Searchable;

    const PROGRESS = 0;
    const SELESAI = 1;

    protected $table = "linen_detail";

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nota_linen',
        'kode_daftar',
        'kode_linen',
        'kode_linen_unit',
        'nama',
        'jml',
        'jml_terima',
        'jml_kembali',
        'berat',
        'kembali',
        'selesai',
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
            'nota_linen',
            'kode_daftar',
            'kode_linen',
            'kode_linen_unit',
            'nama',
            'jml',
            'jml_terima',
            'jml_kembali',
            'berat',
            'kembali',
            'selesai',
            'status',
            'created_by',
            'updated_by',
            'updated_by_name',
        );
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'kode_daftar' => $this->kode_daftar,
            'nama' => $this->nama,
            'nota_linen'=> $this->nota_linen,
            'kode_linen'=> $this->kode_linen,
            'kode_linen_unit'=> $this->kode_linen_unit,
        ];
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
    }

    public function daftar(): BelongsTo
    {
        return $this->belongsTo(Daftar::class, 'kode_daftar','kode');
    }
}
