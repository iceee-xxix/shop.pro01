<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories_files extends Model
{
    use HasFactory;

    public function information()
    {
        return $this->belongsTo(Categories::class, 'categories_id');
    }
}
