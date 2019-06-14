<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommonConfig extends Model
{
  protected $fillable = [
      'key', 'value',
  ];
}
