@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-sm-12">
      <div class="card mb-3">
        <div class="card-header">Check for Staff Data</div>
        <div class="card-body">
          @if (session()->has('alert'))
          <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>{{ session()->get('alert') }}</strong>
          </div>
          @endif
          <form method="get" action="{{ route('admin.usercompare', [], false) }}">
            <div class="form-group row">
              <label for="sinput" class="col-md-4 col-form-label text-md-right">Staff No</label>
              <div class="col-md-4">
                <input id="sinput" class="form-control{{ session()->has('a_type') ? ' is-invalid' : '' }}" type="text" name="staff_no" value="{{ old('staff_no') }}" required minlength="3" autofocus>
              </div>
              <div class="col-md-2">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i>
</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    @if(isset($result))
    <!-- current data in trust -->
    <div class="col-lg-4">
      <div class="card mb-3">
        <div class="card-header bg-info text-white">Current Data in trUSt</div>
        <div class="card-body">
          <div class="form-group">
            <label for="staff_no" class="control-label">Staff No</label>
            <input type="text" class="form-control" id="staff_no" value="{{ $tdata['staff_no'] }}" disabled>
          </div>
          <div class="form-group">
            <label for="staff_name" class="control-label">Staff Name</label>
            <input type="text" class="form-control" id="staff_name" value="{{ $tdata['name'] }}" disabled>
          </div>
          <div class="form-group">
            <label for="persno" class="control-label">Personnel No</label>
            <input type="text" class="form-control" id="persno" value="{{ $tdata['persno'] }}" disabled>
          </div>
          <div class="form-group">
            <label for="pporgunit" class="control-label">Org Unit</label>
            <input type="text" class="form-control" id="pporgunit" value="{{ $tdata['pporgunit'] }} - {{ $tdata['pporgunitdesc'] }}" disabled>
          </div>
          <div class="form-group">
            <label for="report_to" class="control-label">Report To</label>
            <input type="text" class="form-control" id="report_to" value="{{ $tdata['report_to'] }} - {{ $tdata['report_name'] }}" disabled>
          </div>
        </div>
      </div>
    </div>
    <!-- Data from LDAP -->
    <div class="col-lg-4">
      <div class="card mb-3">
        <div class="card-header bg-secondary text-white">Data in LDAP</div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.updateuserdata', [], false) }}">
            @csrf
            <input type="hidden" name="id" value="{{ $uid }}" disabled>
            <div class="form-group">
              <label for="staff_no" class="control-label">Staff No</label>
              <input type="text" class="form-control" id="staff_no" value="{{ $ldata['staff_no'] }}" disabled>
            </div>
            <div class="form-group">
              <label for="staff_name" class="control-label">Staff Name</label>
              <input type="text" class="form-control" id="staff_name" name="name" value="{{ $ldata['name'] }}" disabled>
            </div>
            <div class="form-group">
              <label for="persno" class="control-label">Personnel No</label>
              <input type="text" class="form-control" id="persno" name="persno" value="{{ $ldata['persno'] }}" disabled>
            </div>
            <div class="form-group">
              <label for="pporgunit" class="control-label">Org Unit</label>
              <input type="text" class="form-control" id="pporgunit" name="lob" value="{{ $ldata['pporgunit'] }} - {{ $ldata['pporgunitdesc'] }}" disabled>
            </div>
            <div class="form-group">
              <label for="report_to" class="control-label">Report To</label>
              <input type="text" class="form-control" id="report_to" name="report_to" value="{{ $ldata['report_to'] }} - {{ $ldata['report_name'] }}" disabled>
            </div>
            <div class="form-group mb-0">
              <div class="col text-center">
                <input type="submit" class="btn btn-warning" onclick="return confirm('Confirm to copy data from LDAP?')" value="Use this data" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Custom update -->
    <div class="col-lg-4">
      <div class="card mb-3">
        <div class="card-header bg-danger text-white">Manual</div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.updateuserdata', [], false) }}">
            @csrf
            <input type="hidden" name="id" value="{{ $uid }}" disabled>
            <div class="form-group">
              <label for="staff_no" class="control-label">Staff No</label>
              <input type="text" class="form-control" id="staff_no" value="{{ $staff_no }}" disabled>
            </div>
            <div class="form-group">
              <label for="staff_name" class="control-label">Staff Name</label>
              <input type="text" class="form-control" id="staff_name" name="name" value="{{ $ldata['name'] }}" required>
            </div>
            <div class="form-group">
              <label for="persno" class="control-label">Personnel No</label>
              <input type="text" class="form-control" id="persno" name="persno" value="{{ $ldata['persno'] }}" required>
            </div>
            <div class="form-group">
              <label for="pporgunit" class="control-label">Org Unit (pporgunit only)</label>
              <input type="text" class="form-control" id="pporgunit" name="lob" value="{{ $ldata['pporgunit'] }}" required>
            </div>
            <div class="form-group">
              <label for="report_to" class="control-label">Report To (persno only)</label>
              <input type="text" class="form-control" id="report_to" name="report_to" value="{{ $ldata['report_to'] }}" required>
            </div>
            <div class="form-group mb-0">
              <div class="col text-center">
                <input type="submit" class="btn btn-danger" onclick="return confirm('Confirm to use this data?')" value="Overwrite" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    @endif
  </div>
</div>
@endsection
