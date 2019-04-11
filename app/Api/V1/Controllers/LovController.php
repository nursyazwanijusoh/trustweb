<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\ActivityType;
use App\TaskCategory;

/**
 * Controller for buildings / tables
 */
class LovController extends Controller
{
  // ============================================
  // = ActivityType
  // ============================================

    function atCreate(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'descr' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $build = new ActivityType;
      $build->descr = $req->descr;
      $build->status = 1;

      if($req->filled('remark')){
        $build->remark = $req->remark;
      }

      $build->save();

      return $this->respond_json(200, 'data saved', $build);
    }

    function atEdit(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'at_id' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $build = ActivityType::where('id', $req->at_id)->first();

      if($build){
        if($req->filled('descr')){
          $build->descr = $req->descr;
        }

        if($req->filled('remark')){
          $build->remark = $req->remark;
        }

        if($req->filled('status')){
          $build->status = $req->status;
        }

        $build->save();
        return $this->respond_json(200, 'data saved', $build);
      } else {
        return $this->respond_json(404, 'record not found', $input);
      }
    }

    function atDelete(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'at_id' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $build = ActivityType::find($req->at_id);

      if($build){
        // then delete the ActivityType
        // $build->delete();
        $build->status = 0;
        $build->save();

        return $this->respond_json(200, 'record set to inactive', $build);
      } else {
        return $this->respond_json(404, 'record not found', $input);
      }

    }

    function atSearch(Request $req){
      $input = app('request')->all();

  		$rules = [
  			'key' => ['required'],
        'value' => ['required']
  		];

  		$validator = app('validator')->make($input, $rules);
  		if($validator->fails()){
  			return $this->respond_json(412, 'Invalid input', $input);
  		}

      $builds = ActivityType::where($req->key, $req->value)->get();

      return $this->respond_json(200, 'result', $builds);

    }


    // ================
    // = task type    =
    // ================

      function ttCreate(Request $req){
        $input = app('request')->all();

        $rules = [
          'descr' => ['required']
        ];

        $validator = app('validator')->make($input, $rules);
        if($validator->fails()){
          return $this->respond_json(412, 'Invalid input', $input);
        }

        $build = new TaskCategory;
        $build->descr = $req->descr;

        if($req->filled('remark')){
          $build->remark = $req->remark;
        }

        if($req->filled('type')){
          $build->type = $req->type;
        }

        $build->status = 1;

        $build->save();

        return $this->respond_json(200, 'data saved', $build);
      }

      function ttEdit(Request $req){
        $input = app('request')->all();

        $rules = [
          'tt_id' => ['required']
        ];

        $validator = app('validator')->make($input, $rules);
        if($validator->fails()){
          return $this->respond_json(412, 'Invalid input', $input);
        }

        $build = TaskCategory::find($req->tt_id);

        if($build){
          if($req->filled('remark')){
            $build->remark = $req->remark;
          }

          if($req->filled('type')){
            $build->type = $req->type;
          }

          if($req->filled('status')){
            $build->status = $req->status;
          }

          if($req->filled('descr')){
            $build->descr = $req->descr;
          }

          $build->save();
          return $this->respond_json(200, 'data saved', $build);
        } else {
          return $this->respond_json(404, 'record not found', $input);
        }
      }

      function ttDelete(Request $req){
        $input = app('request')->all();

        $rules = [
          'tt_id' => ['required']
        ];

        $validator = app('validator')->make($input, $rules);
        if($validator->fails()){
          return $this->respond_json(412, 'Invalid input', $input);
        }

        $build = TaskCategory::where('id', $req->tt_id)->first();

        if($build){
          // $build->delete();
          $build->status = 0;
          $build->save();
          return $this->respond_json(200, 'record set to inactive', $build);
        } else {
          return $this->respond_json(404, 'record not found', $input);
        }

      }

      function ttSearch(Request $req){
        $input = app('request')->all();

        $rules = [
          'key' => ['required'],
          'value' => ['required']
        ];

        $validator = app('validator')->make($input, $rules);
        if($validator->fails()){
          return $this->respond_json(412, 'Invalid input', $input);
        }

        $builds = TaskCategory::where($req->key, $req->value)->get();

        return $this->respond_json(200, 'result', $builds);

      }
}
