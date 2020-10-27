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
        'password', 'remember_token'
    ];

    public function accessTokens()
    {
        return $this->hasMany('App\OauthAccessToken');
    }

    public function verifyUser()
    {
        return $this->hasOne('App\VerifyUser');
    }

    public function Partner()
    {
        return $this->belongsTo('App\Partner', 'partner_id');
    }

    public function Division()
    {
        if (isset($this->unit_id)) {
            return $this->belongsTo('App\Unit', 'unit_id');
        } else {
            if ($this->isvendor == 1) {
                return Unit::where('pporgunit', 123)->first();
            } else {
                return Unit::where('pporgunit', 0)->first();
            }
        }
    }

    public function Boss()
    {
        return $this->hasOne(User::class, 'persno', 'report_to');
    }

    public function Attendance()
    {
        return $this->hasOne('App\Attendance', 'id', 'curr_attendance');
    }

    public function Avatar()
    {
        return $this->belongsTo('App\Avatar', 'avatar_rank', 'rank');
    }

    public function divName()
    {
        if ($this->isvendor == 1) {
            return $this->Partner->comp_name;
        } else {
            return $this->Division->pporgunitdesc;
        }
    }

    public function BauExperiences()
    {
        return $this->belongsToMany(BauExperience::class);
    }

    public function NoBauExperiences()
    {
        return BauExperience::whereNotIn('id', $this->BauExperiences->pluck('id'));
    }

    public function GetSkillLevel($common_skill_id)
    {
        $ps = PersonalSkillset::where('staff_id', $this->id)
        ->where('common_skill_id', $common_skill_id)->first();

        if ($ps) {
            return $ps->slevel();
        } else {
            return '0 - N/A';
        }
    }

    public function LastLocation()
    {
        return $this->hasOne(LocationHistory::class, 'id', 'last_location_id');
    }

    public function Section()
    {
        if (isset($this->section_id)) {
            $unit = SubUnit::find($this->section_id);
            if ($unit) {
                return $unit->ppsuborgunitdesc;
            } else {
                return $this->subunit;
            }
        } else {
            return $this->subunit;
        }
    }

    public function CompGroups()
    {
        return $this->belongsToMany(CompGroup::class);
    }

    public function getPersonalSkillset()
    {
      $cs= new CommonSkillset;
        return $this->hasMany(PersonalSkillset::class, 'staff_id')
        ->join($cs->getTable() .' as cs','common_skill_id','cs.id')
        ->get(['cs.id as skill_id','name','level']);

      //  return $this->hasMany(PersonalSkillset::class, 'staff_id')
      //->with('commonSkill')->get()->flatten(3);
     }

    

    public function Votes()
    {
        return $this->belongsToMany(Poll::class);
    }
}
