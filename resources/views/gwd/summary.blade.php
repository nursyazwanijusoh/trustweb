@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Activity Summary for month {{ $damon }}</div>
                <div class="card-body">
                  <form method="GET" action="{{ route('staff.list', [], false) }}">
                    <!-- @csrf -->
                    <input type="hidden" name="staff_id" value="{{ $staffid }}" >
                    <div class="form-group row">
                        <label for="actdate" class="col-md-4 col-form-label text-md-right">Month to display</label>
                        <div class="col-md-4">
                          <input type="date" class="form-control" name="actdate" id="actdate" value="{{ $curdate }}"/>
                        </div>
                        <div class="col-md-2">
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                  </form>
                  <br />
                </div>
              </div>
              <br />
              <div class="card">
                <div class="card-header">List of Activities</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="taskdetailtable" class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Division</th>
                          <th scope="col">Total Registered</th>
                          <th scope="col">Total Diary Hours</th>
                          <th scope="col">Average Daily Hours Per Person</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($activities as $acts)
                        <tr>
                          <td>{{ $acts->activity_date }}</td>
                          <td>{{ $acts->ActType->descr }}</td>
                          <td>{{ $acts->parent_number }}</td>
                          <td>{{ $acts->details }}</td>
                          <td>{{ $acts->hours_spent }}</td>
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
