<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersDetails extends Model
{
    use HasFactory;

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id')->withTrashed();
    }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function option()
    {
        return $this->belongsTo(MenuOption::class, 'option_id');
    }
}
