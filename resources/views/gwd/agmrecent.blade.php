@extends('layouts.app')

@section('page-css')
<link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
              <div class="card mb-3">
                <div class="card-header">Productivity % for {{ $s_name }}. Overall: {{ number_format($tavg, 2) }}%</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="taskdetailtable" class="table table-striped table-bordered table-hover">
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
                          <td>{{ number_format($adate, 2) }}</td>
                          @endforeach
                          <td>{{ $acts['avg'] }}</td>
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
