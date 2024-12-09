<?php

namespace App\Models\Contracts;

interface SearchInterface {
    public function scopeWhenSearch($query, $search);
}
