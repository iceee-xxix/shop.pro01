<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function category()
    {
        return $this->belongsTo(Categories::class, 'categories_id')->withTrashed();
    }

    public function files()
    {
        return $this->hasOne(MenuFiles::class, 'menu_id');
    }

    public function option(){
        return $this->hasMany(MenuOption::class, 'menu_id');
    }
}
