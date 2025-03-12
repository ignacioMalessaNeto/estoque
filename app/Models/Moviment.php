<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Moviment extends Model
{

    public function out()
    {
        return $this->belongsTo(Out::class, 'id_out');
    }

    public function entrie()
    {
        return $this->belongsTo(Stock::class, 'id_entrie');
    }
}
