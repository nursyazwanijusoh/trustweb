@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
              <div class="card">
                <div class="card-header">Where {{ $username }} has been for the past 1 month</div>
                <div class="card-body">
                  <table id="taskdetailtable" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">In</th>
                        <th scope="col">Out</th>
                        <th scope="col">Seat</th>
                        <th scope="col">Location?</th>
                        <th scope="col">Out Reason</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($activities as $acts)
                      <tr>
                        <td>{{ $acts->checkin_time }}</td>
                        <td>{{ $acts->checkout_time }}</td>
                        <td>{{ $acts->place->label }}</td>
                        <td><a href="https://www.google.com/maps/search/?api=1&query={{ $acts->latitude . ',' . $acts->longitude }}" target="_blank">See In Map</a></td>
                        <td>{{ $acts->remark }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
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
