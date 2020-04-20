<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function verifyUser(){
      return $this->hasOne('App\VerifyUser');
    }

    public function Partner(){
      return $this->belongsTo('App\Partner', 'partner_id');
    }

    public function Division(){
      return $this->belongsTo('App\Unit', 'unit_id');
    }

    public function Boss(){
      return $this->hasOne(User::class, 'persno', 'report_to');
    }

    public function Attendance(){
      return $this->hasOne('App\Attendance', 'id', 'curr_attendance');
    }

    public function Avatar(){
      return $this->belongsTo('App\Avatar', 'avatar_rank', 'rank');
    }

    public function divName(){
      if($this->isvendor == 1){
        return $this->Partner->comp_name;
      } else {
        return $this->Division->pporgunitdesc;
      }
    }

    public function BauExperiences(){
      return $this->belongsToMany(BauExperience::class);
    }

    public function NoBauExperiences(){
      return BauExperience::whereNotIn('id', $this->BauExperiences->pluck('id'))->get();
    }

    public function GetSkillLevel($common_skill_id){
      $ps = PersonalSkillset::where('staff_id', $this->id)
        ->where('common_skill_id', $common_skill_id)->first();

      if($ps){
        return $ps->slevel();
      } else {
        return '0 - N/A';
      }
    }

    public function LastLocation(){
      return $this->hasOne(LocationHistory::class, 'id', 'last_location_id');
    }

    public function Section(){
      if(isset($this->section_id)){
        $unit = SubUnit::find($this->section_id);
        if($unit){
          return $unit->ppsuborgunitdesc;
        } else {
          return $this->subunit;
        }
      } else {
        return $this->subunit;
      }
    }

}
