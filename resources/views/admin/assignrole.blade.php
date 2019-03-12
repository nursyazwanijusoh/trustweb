@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Bulk Staff Account Management</div>
                @if(isset($alert))
                <div class="alert alert-warning" role="alert">{{ $alert }}</div>
                @endif
                <div class="card-body">
                  <form method="POST" action="{{ route('admin.addsr', [], false) }}">
                    @csrf
                    <h5 class="card-title">Staff access assignment</h5>
                    <!-- <div class="form-group row">
                        <label for="lob" class="col-md-4 col-form-label text-md-right">Group</label>
                        <div class="col-md-6">
                            <input id="lob" type="text" name="lob" maxlength="15" required autofocus>
                        </div>
                    </div> -->
                    <div class="form-group row">
                      <label for="srole" class="col-md-4 col-form-label text-md-right">Role</label>
                      <div class="col-md-6">
                        <select class="form-control" id="srole" name="srole">
                          @if ($role == 0)
                          <option value="0" title="Great power comes with great responsibilities">Super Admin</option>
                          @endif

                          <option value="1" title="Be responsible">Floor Admin</option>
                          <option value="2" title="Yea.. The Bosses">VIP</option>
                          <option value="3" title="The plebians goes here" selected>Staff</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                        <label for="staffs" class="col-md-4 col-form-label text-md-right">List of Staff No</label>
                        <div class="col-md-6">
                          <textarea rows="5" class="form-control" id="staffs" name="staffs"></textarea>
                        </div>
                    </div>

                    <h5 class="card-title">Select which floor to give access to these staff</h5>
                    @foreach($blist as $abuild)
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="{{ $abuild['id'] }}" id="defaultCheck{{ $abuild['id'] }}" name="cbfloor[]">
                      <label class="form-check-label" for="defaultCheck{{ $abuild['id'] }}">
                        {{ $abuild['floor_name'] . '@' . $abuild['building_name'] }}
                      </label>
                    </div>
                    @endforeach

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Assign Staff</button>
                        </div>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
