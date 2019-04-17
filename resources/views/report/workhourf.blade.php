@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">View work-hours summary</div>
                <div class="card-body">
                  <form method="POST" action="{{ route('reports.workhourf', [], false) }}" id="whform">
                    @csrf
                    <h5 class="card-title">Date range</h5>
                    <div class="form-group row">
                        <label for="fdate" class="col-md-4 col-form-label text-md-right">From</label>
                        <div class="col-md-6">
                          <input type="date" name="fdate" id="fdate" value="{{ $fromdate }}"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="todate" class="col-md-4 col-form-label text-md-right">To (exclusive)</label>
                        <div class="col-md-6">
                          <input type="date" name="todate" id="todate" value="{{ $curdate }}"/>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="pporgunit" class="col-md-4 col-form-label text-md-right">Division</label>
                      <div class="col-md-6">
                        <select class="form-control" id="pporgunit" name="pporgunit" onchange="event.preventDefault();
                                      document.getElementById('whform').submit();">
                          @foreach ($divlist as $atask)
                          <option value="{{ $atask['pporgunit'] }}" {{ $atask['sel'] }} >{{ $atask['divname'] . ' (' . $atask['regcount'] . ')' }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary" name="subtype" value="lob">Get Division Report</button>
                        </div>
                    </div>
                    <div class="form-group row  {{ $gotunit }}">
                      <label for="subunit" class="col-md-4 col-form-label text-md-right">Division</label>
                      <div class="col-md-6">
                        <select class="form-control" id="subunit" name="subunit">
                          @foreach ($unitlist as $atask)
                          <option value="{{ $atask->subunit }}" >{{ $atask->subunit . ' (' . $atask->reg_count . ')' }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row mb-0  {{ $gotunit }}">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary" name="subtype" value="subunit" {{ $gotunit }}>Get Unit Report</button>
                        </div>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
