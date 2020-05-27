@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center no-gutters">
        <div class="col-lg-4">
            <div class="card m-1">
                <div class="card-header">Select group and time range</div>
                <div class="card-body">
                  @if (session()->has('alert'))
                  <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ session()->get('alert') }}</strong>
                  </div>
                  @endif
                  <form method="GET" action="{{ route('report.gwd.summary', [], false) }}">
                    <div class="form-group row">
                      <label for="gid" class="col-md-4 col-form-label text-md-right">Group</label>
                      <div class="col-md-7">
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
                        <div class="col-md-7">
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
                        <div class="col-md-7">
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
                            <button type="submit" name="action" value="graph" class="btn btn-primary m-1">Get Summary</button>
                            <button type="submit" name="action" value="excel" class="btn btn-primary m-1">Generate Report</button>
                            <button type="submit" name="action" value="datatable" class="btn btn-primary m-1">View Details (beta)</button>
                            <button type="submit" name="action" value="verticaldate" class="btn btn-primary m-1">Vertical Detail</button>
                        </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-lg-8">
              <div class="card m-1">
                <div class="card-header">Reports History</div>
                <div class="card-body text-center">
                  <div class="table-responsive">
                  <table id="repothist" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Action</th>
                        <th scope="col">Status Date</th>
                        <th scope="col">Group</th>
                        <th scope="col">Status</th>
                        <th scope="col">Data From</th>
                        <th scope="col">Data To</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($rpthist as $acts)
                      <tr>
                        @if($acts->status == 'Completed')
                        <td><form action="{{ route('report.gwd.gsummdl', [], false)}}"
                          method="post">
                          <input type="hidden" name="bjid" value="{{ $acts->id }}" />
                          @csrf
                          <button type="submit" class="btn btn-sm btn-info" title="Download"><i class="fa fa-download"></i></button>
                        </form></td>
                        @else
                        <td>...</td>
                        @endif
                        <td>{{ $acts->updated_at }}</td>
                        <td>{{ $acts->CGroup->name }}</td>
                        <td>{{ $acts->status }}</td>
                        <td>{{ (new \Carbon\Carbon($acts->from_date))->format('Y-m-d') }}</td>
                        <td>{{ (new \Carbon\Carbon($acts->to_date))->format('Y-m-d') }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                </div>
            </div>
          </div>
          <div class="col-lg-12">
              @if(isset($rptdata))
              <div class="card m-1" id="summa">
                <div class="card-header">Performance Summary</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="perfsameri" class="table table-striped table-hover table-bordered">
                      <thead>
                        <tr>
                          <th scope="col">Division</th>
                          <th scope="col">0</th>
                          <th scope="col">1 - 49</th>
                          <th scope="col">50 - 69</th>
                          <th scope="col">70 - 100</th>
                          <th scope="col">100+</th>
                          <th scope="col">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($sumtable as $acts)
                        <tr>
                          <td><a href="{{ route('report.gwd.divsum', ['did' => $acts['div_id'], 'fdate' => $sdate, 'tdate' => $edate, 'action' => 'graph'], false) }}">{{ $acts['div_name'] }}</a></td>
                          <td>{{ $acts['t_0'] }}</td>
                          <td>{{ $acts['t_A'] }}</td>
                          <td>{{ $acts['t_B'] }}</td>
                          <td>{{ $acts['t_C'] }}</td>
                          <td>{{ $acts['t_D'] }}</td>
                          <td>{{ $acts['total'] }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="card m-1">
                <div class="card-header">Infographic</div>
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
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
@if(isset($rptdata))
<script type="text/javascript">
$(document).ready(function() {
  $('#perfsameri').DataTable({
      "order": [[ 1, "desc" ]]
    });
  $('#repothist').DataTable({
      "order": [[ 1, "desc" ]]
    });

  document.getElementById('summa').scrollIntoView();

} );
</script>
@else
<script type="text/javascript">
$(document).ready(function() {
  $('#repothist').DataTable({
      "order": [[ 1, "desc" ]]
    });
} );
</script>
@endif
@stop
