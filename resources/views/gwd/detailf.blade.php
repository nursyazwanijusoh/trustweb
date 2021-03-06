@extends('layouts.app')

@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Get Detailed Diary Report</div>
                <div class="card-body">
                  <form method="get" action="{{ route('report.gwd.detail', [], false) }}" id="whform">
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
                          <option value="{{ $atask['pporgunit'] }}" {{ $atask['sel'] }} >{{ $atask['pporgunit'] }} - {{ $atask['divname'] . ' (' . $atask['regcount'] . ')' }}</option>
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
                      <label for="subunit" class="col-md-4 col-form-label text-md-right">Unit</label>
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


@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#pporgunit').select2();
    $('#subunit').select2();

});

</script>
@endsection
