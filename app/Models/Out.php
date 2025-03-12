<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Out extends Model
{

    protected $hidden = ['id_user', 'id_stock', 'recipient'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'recipient');
    }

    public function moviment()
    {
        return $this->hasOne(Moviment::class, 'id_out');
    }

    public function stock(){
        return $this->hasOne(Stock::class, 'id');
    }
}
