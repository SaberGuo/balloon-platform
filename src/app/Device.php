<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Base
{
  use SoftDeletes;

  protected $table = 'device';

  protected $fillable = ['name', 'sn'];

  public function configs() {
      return $this->hasMany('App\DeviceConfig');
  }

  public function config() {
      return $this->configs()->orderBy('updated_at', 'desc')->limit(1);
  }
}
