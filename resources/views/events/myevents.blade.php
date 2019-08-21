@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
              <div class="card">
                <div class="card-header">My Events</div>
                <div class="card-body">
                  <table id="taskdetailtable" class="table table-striped table-bordered table-responsive table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Start Time</th>
                        <th scope="col">End Time</th>
                        <th scope="col">Name</th>
                        <th scope="col">Meeting Area</th>
                        <th scope="col">Floor Name</th>
                        <th scope="col">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($elist as $acts)
                      <tr>
                        <td>{{ $acts->start_time }}</td>
                        <td>{{ $acts->end_time }}</td>
                        <td><a href="{{ route('area.evdetail', ['id' => $acts->id], false) }}">{{ $acts->event_name }}</a></td>
                        <td>{{ $acts->Location->label }}</td>
                        <td>{{ $acts->Location->building->floor_name }}</td>
                        <td>{{ $acts->status }}</td>
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
    $('#taskdetailtable').DataTable({
      responsive: true
    });
} );
</script>
@stop
