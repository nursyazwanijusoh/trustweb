@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
              <div class="card">
                <div class="card-header">Recent productivity % for {{ $s_name }}</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="taskdetailtable" class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Unit</th>
                          <th scope="col">Name</th>
                          @foreach($daterange as $adate)
                          <th scope="col">{{ date_format($adate, 'j M') }}</th>
                          @endforeach
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($sdata as $acts)
                        <tr>
                          <td>{{ $acts['unit'] }}</td>
                          <td><a href="{{ route('staff', ['staff_id' => $acts['id']], false) }}">{{ $acts['name'] }}</a></td>
                          @foreach($acts['recent_perf'] as $adate)
                          <td>{{ $adate }}</td>
                          @endforeach
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
<!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" defer>
$(document).ready(function() {
    $('#taskdetailtable').DataTable();
} );
</script>
@stop
