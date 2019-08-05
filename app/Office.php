<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{

  // protected $hidden = array('building');

  public function building(){
    return $this->hasMany('App\building');
  }

  public function creator(){
    return $this->hasOne('App\User', 'created_by');
  }

  public function editor(){
    return $this->hasOne('App\User', 'modified_by');
  }

  public function buildingWithAsset($type){
    $blist = $this->building;
    $totalc = 0;

    foreach ($blist as $key => $value) {
      $asset = $value->Asset($type);
      if($asset->count() == 0){
        unset($blist[$key]);
      } else {
        $totalc += $asset->count();
        $value->assetcount = $asset->count();
      }
    }

    $this->totalasset = $totalc;
    return $blist;
  }
}
