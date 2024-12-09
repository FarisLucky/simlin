<?php

namespace App\Models\Contracts;

interface SortInterface {
    public function scopeWhenSort($query, $keyBy, $keyType);
}
