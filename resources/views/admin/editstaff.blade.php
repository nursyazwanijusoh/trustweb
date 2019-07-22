@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Edit Individual Staff</div>
                @if(isset($alert))
                <div class="alert alert-warning" role="alert">{{ $alert }}</div>
                @endif
                <div class="card-body">
                  <form method="POST" action="{{ route('admin.findst', [], false)}}">
                    @csrf
                    <h5 class="card-title">Find Staff</h5>
                    <div class="form-group row">
                        <label for="staff_no" class="col-md-4 col-form-label text-md-right">Staff No</label>
                        <div class="col-md-6">
                            <input id="staff_no" class="form-control" type="text" name="staff_no" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Find Staff</button>
                        </div>
                    </div>
                  </form>
                </div>
                <div class="card-header"> </div>
                <div class="card-body">
                  <form method="POST" action="{{ route('admin.upst', [], false) }}">
                    @csrf
                    <h5 class="card-title">Staff Info</h5>
                    <!-- <div class="form-group row">
                        <label for="lob" class="col-md-4 col-form-label text-md-right">Group</label>
                        <div class="col-md-6">
                            <input id="lob" type="text" name="lob" maxlength="15" required autofocus>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label for="staff_no2" class="col-md-4 col-form-label text-md-right">Staff No</label>
                        <div class="col-md-6">
                            <input id="staff_no2" class="form-control" type="text" name="staff_no2" value="{{ $staffdata['STAFF_NO'] }}" readonly  />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staff_name" class="col-md-4 col-form-label text-md-right">Staff Name</label>
                        <div class="col-md-6">
                            <input id="staff_name" class="form-control" type="text" name="staff_name" value="{{ $staffdata['NAME'] }}" readonly  />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="unit_disc" class="col-md-4 col-form-label text-md-right">Unit</label>
                        <div class="col-md-6">
                            <input id="unit_disc" class="form-control" type="text" name="unit_disc" value="{{ $staffdata['UNIT'] }}" readonly  />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="subunit_disc" class="col-md-4 col-form-label text-md-right">Sub Unit</label>
                        <div class="col-md-6">
                            <input id="subunit_disc" class="form-control" type="text" name="subunit_disc" value="{{ $staffdata['SUBUNIT'] }}" readonly  />
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="srole" class="col-md-4 col-form-label text-md-right">Role</label>
                      <div class="col-md-6">
                        <select class="form-control" id="srole" name="srole" autofocus>
                          @if ($role == 0)
                          <option value="0" title="Great power comes with great responsibilities" {{ $selected[0]}}>Super Admin</option>
                          @endif
                          <option value="1" title="Be responsible" {{ $selected[1] }}>Floor Admin</option>
                          <option value="2" title="Yea.. The Bosses" {{ $selected[2] }}>VIP</option>
                          <option value="3" title="The plebians goes here" {{ $selected[3] }}>Staff</option>
                        </select>
                      </div>
                    </div>

                    <h5 class="card-title">Select which floor to give access to this staff</h5>
                    @foreach($blist as $abuild)
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="{{ $abuild['id'] }}" id="defaultCheck{{ $abuild['id'] }}" {{ $abuild['chk'] }} name="cbfloor[]">
                      <label class="form-check-label" for="defaultCheck{{ $abuild['id'] }}">
                        {{ $abuild['unit'] . ' -> ' . $abuild['floor_name'] . '@' . $abuild['building_name'] }}
                      </label>
                    </div>
                    @endforeach

                    <input type="hidden" name="lob" value="{{ $staffdata['DEPARTMENT'] }}" />
                    <input type="hidden" name="mobile" value="{{ substr($staffdata['MOBILE_NO'], 0, 14) }}" />
                    <input type="hidden" name="email" value="{{ $staffdata['EMAIL'] }}" />
                    <input type="hidden" name="id" value="{{ $staffdata['id'] }}" />

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary" {{ $staffdata['btn_state'] }}>{{ $staffdata['btn_txt'] }}</button>
                        </div>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
