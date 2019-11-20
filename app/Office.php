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

  public function buildingWithAsset($type, $canbook = 0){
    $blist = $this->building->sortBy('floor_name');
    $totalc = 0;
    $fblist = [];

    foreach ($blist as $key => $value) {
      $asset = $value->Asset($type, $canbook);
      if($asset->count() != 0){
        $totalc += $asset->count();
        $value->assetcount = $asset->count();

        array_push($fblist, $value);

      }
    }

    $this->totalasset = $totalc;
    return $fblist;
  }
}
