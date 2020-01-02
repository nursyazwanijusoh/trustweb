@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Select group and time range</div>
                <div class="card-body">
                  <form method="GET" action="{{ route('report.gwd.summary', [], false) }}">
                    <div class="form-group row">
                      <label for="gid" class="col-md-4 col-form-label text-md-right">Group</label>
                      <div class="col-md-6">
                        <select class="form-control{{ $errors->has('gid') ? ' is-invalid' : '' }}" id="gid" name="gid" required>
                          @foreach ($glist as $atask)
                          <option value="{{ $atask->id }}" {{ $atask->selected }} >{{ $atask->name }}</option>
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
                          <input type="date" class="form-control{{ $errors->has('fdate') ? ' is-invalid' : '' }}" name="fdate" id="fdate" value="{{ old('fdate', $sdate) }}" required />
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
                          <input type="date" class="form-control{{ $errors->has('tdate') ? ' is-invalid' : '' }}" name="tdate" id="tdate" value="{{ old('tdate', $edate) }}" required />
                          @if ($errors->has('tdate'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('tdate') }}</strong>
                              </span>
                          @endif
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col text-center">
                            <button type="submit" name="action" value="graph" class="btn btn-primary">Get Summary</button>
                            <button type="submit" name="action" value="excel" class="btn btn-primary">Download Details</button>
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
                  {!! $sumchart->render() !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
@stop
