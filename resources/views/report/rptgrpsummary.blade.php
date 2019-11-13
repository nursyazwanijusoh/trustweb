@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Select group and time range</div>
                <div class="card-body">
                  <form method="POST" action="{{ route('reports.gwd.dogsum', [], false) }}">
                    @csrf
                    <div class="form-group row">
                      <label for="gid" class="col-md-4 col-form-label text-md-right">Group</label>
                      <div class="col-md-6">
                        <select class="form-control{{ $errors->has('gid') ? ' is-invalid' : '' }}" id="gid" name="gid" required>
                          @foreach ($glist as $atask)
                          <option value="{{ $atask->id }}" selected="{{ $atask->selected }}" >{{ $atask->name }}</option>
                          @endforeach
                        </select>
                        @if ($errors->has('gid'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('gid') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                    <div class="form-group row">
                        <label for="fdate" class="col-md-4 col-form-label text-md-right">From</label>
                        <div class="col-md-6">
                          <input type="date" class="form-control{{ $errors->has('fdate') ? ' is-invalid' : '' }}" name="fdate" id="fdate" value="{{ $sdate }}" required />
                          @if ($errors->has('fdate'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('fdate') }}</strong>
                              </span>
                          @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tdate" class="col-md-4 col-form-label text-md-right">To</label>
                        <div class="col-md-6">
                          <input type="date" class="form-control{{ $errors->has('tdate') ? ' is-invalid' : '' }}" name="tdate" id="tdate" value="{{ $edate }}" required />
                          @if ($errors->has('tdate'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('tdate') }}</strong>
                              </span>
                          @endif
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                        </div>
                    </div>
                  </form>
                  <br />
                </div>
              </div>
              <br />
              @if(isset($rptdata))
              <div class="card">
                <div class="card-header">Summary</div>
                <div class="card-body">

                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('page-js')

@stop
