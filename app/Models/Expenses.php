<?php

namespace App\Models;

use App\Http\Controllers\admin\CategoryExpenses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(CategoriesExpenses::class, 'category_id')->withTrashed();
    }
}
