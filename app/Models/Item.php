<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'itens';
    public $timestamps = false;
    public function stocks(){
        return $this->hasMany(Stock::class, 'id_item');
    }
}
