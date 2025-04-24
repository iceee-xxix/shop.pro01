<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function files()
    {
        return $this->hasOne(Categories_files::class, 'categories_id');
    }
    
    public function menu()
    {
        return $this->hasMany(Menu::class, 'categories_id');
    }
}
