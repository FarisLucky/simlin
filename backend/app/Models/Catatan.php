<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catatan extends Model
{

    protected $table = "catatan";

    protected $fillable = [
        'id',
        'id_relation',
        'ket',
    ];

    public $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeSelectIdx($query)
    {
        return $query->select(
            'id',
            'id_relation',
            'ket',
        );
    }
}
