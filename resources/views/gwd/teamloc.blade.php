@extends('layouts.app')

@section('page-css')
<link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card mb-3">
        <div class="card-header">
          Select date to display
        </div>
        <div class="card-body">
          <form method="GET" action="{{ route('report.team.locations', [], false) }}">
            <!-- @csrf -->
            <input type="hidden" name="uid" value="{{ $agm_id }}" >
            <div class="form-group row">
                <label for="sdate" class="col-md-2 col-form-label text-md-right">Date </label>
                <div class="col-md-3">
                  <input type="date" class="form-control{{ $errors->has('sdate') ? ' is-invalid' : '' }}" name="sdate" id="sdate" value="{{ old('sdate', $s_date) }}" max="{{ date('Y-m-d') }}" required/>
                  @if ($errors->has('sdate'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('sdate') }}</strong>
                      </span>
                  @endif
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-primary">View</button>
                </div>
            </div>
          </form>
        </div>
      </div>
      <div class="card mb-3">
        <div class="card-header">{{ $s_name }} team member - <b>Last known location</b></div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="taskdetailtable" class="table table-sm table-bordered table-hover">
              <thead>
                <tr>
                  <th scope="col">Unit</th>
                  <th scope="col">Name</th>
                  <th scope="col">Team Ind</th>
                  <th scope="col">Time</th>
                  <th scope="col">Activity</th>
                  <th scope="col">Address</th>
                  <th scope="col">Map</th>
                </tr>
              </thead>
              <tbody>
                @foreach($sdata as $acts)
                <tr>
                  <td>{{ $acts['subunit'] }}</td>
                  <td><a href="{{ route('staff', ['staff_id' => $acts['id']], false) }}">{{ $acts['name'] }}</a></td>
                  <td>{{ $acts['teamab'] }}</td>
                  <td>{{ $acts['ltime'] }}</td>
                  <td class="{{ $acts['bg'] }}">{{ $acts['lact'] }}</td>
                  <td>{{ $acts['laddr'] }}</td>
                  <td>
                    @if($acts['g'] == true)
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $acts['llat'] . ',' . $acts['llong'] }}" target="_blank">
                      <button class="btn btn-sm btn-primary"><i class="fa fa-map"></i></button>
                    </a>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
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
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script> -->
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
