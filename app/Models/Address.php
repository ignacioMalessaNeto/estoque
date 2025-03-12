<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';
    public $timestamps = false;

    public function stocks(){
        return $this->hasMany(Stock::class, 'id_address');
    }
}
