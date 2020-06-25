@extends('layouts.app')

@section('page-css')
<link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card mb-3">
        <div class="card-header">
          Select data range
        </div>
        <div class="card-body">
          <form method="GET" action="{{ route('report.agm.recent', [], false) }}">
            <!-- @csrf -->
            <input type="hidden" name="agm_id" value="{{ $agm_id }}" >
            <div class="form-group row">
                <label for="sdate" class="col-md-2 col-form-label text-md-right">From </label>
                <div class="col-md-3">
                  <input type="date" class="form-control{{ $errors->has('sdate') ? ' is-invalid' : '' }}" name="sdate" id="sdate" value="{{ old('sdate', $s_date) }}" max="{{ date('Y-m-d') }}" required/>
                  @if ($errors->has('sdate'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('sdate') }}</strong>
                      </span>
                  @endif
                </div>
                <label for="edate" class="col-md-2 col-form-label text-md-right">To </label>
                <div class="col-md-3">
                  <input type="date" class="form-control{{ $errors->has('edate') ? ' is-invalid' : '' }}" name="edate" id="edate" value="{{ old('edate', $e_date) }}" max="{{ date('Y-m-d') }}" required/>
                  @if ($errors->has('edate'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('edate') }}</strong>
                      </span>
                  @endif
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-primary">Get</button>
                </div>
            </div>
          </form>
        </div>
      </div>
      <div class="card mb-3">
        <div class="card-header">Productivity % for {{ $s_name }}. Overall: {{ number_format($tavg, 2) + 0 }}%</div>
        <div class="card-body">
          <p class="card-text">Legend: <button class="btn btn-warning">Below Target 85%</button>
            <button class="btn btn-secondary">On Leave</button>
          </p>
          <div class="table-responsive">
            <table id="taskdetailtable" class="table table-sm table-bordered table-hover">
              <thead>
                <tr>
                  <th scope="col">Unit</th>
                  <th scope="col">Name</th>
                  @foreach($daterange as $adate)
                  <th scope="col">{{ date_format($adate, 'D j-M') }}</th>
                  @endforeach
                  <th scope="col">Total %</th>
                </tr>
              </thead>
              <tbody>
                @foreach($sdata as $acts)
                <tr>
                  <td>{{ $acts['unit'] }}</td>
                  <td><a href="{{ route('staff', ['staff_id' => $acts['id']], false) }}">{{ $acts['name'] }}</a></td>
                  @foreach($acts['recent_perf'] as $adate)
                  @if($adate['isonleave'])
                  <td class="bg-secondary">
                  @elseif($adate['perc'] < 85)
                  <td class="bg-warning">
                  @else
                  <td>
                  @endif
                  {{ $adate['actual'] + 0 }} / {{ $adate['expected'] + 0 }}
                  </td>
                  @endforeach
                  <td>{{ $acts['avg']['perc'] + 0 }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">In Graph</div>
        <div class="card-body">
          {!! $chart->render() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-js')
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js "></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script type="text/javascript" defer>
$(document).ready(function() {
    $('#taskdetailtable').DataTable({
      dom: 'Bfrtip',
      buttons: [
          'csv', 'excel'
      ]
    });
} );
</script>

@stop
