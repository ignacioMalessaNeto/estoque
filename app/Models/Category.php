<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    public $timestamps = false;

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'id_category');
    }
}
