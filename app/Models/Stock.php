<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';

    protected $hidden = ['id_item', 'id_category', 'id_address', 'create_by'];

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'id_address');
    }

    public function create()
    {
        return $this->belongsTo(User::class, 'create_by');
    }

    public function moviment()
    {
        return $this->hasOne(Moviment::class, 'id_entrie');
    }
}
