<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SkillType extends Model
{
  public function Category(){
    return $this->belongsTo(SkillCategory::class, 'skill_category_id');
  }
}
