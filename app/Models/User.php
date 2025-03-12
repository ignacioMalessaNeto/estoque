<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
  protected $table = 'users';
  protected $hidden = [  'password'];

  public function stocks() {
    return $this->hasMany(Stock::class, 'id_user');
  }
}
