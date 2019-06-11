@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Task Details</div>
                <div class="card-body">
                  <form method="POST" action="{{ route('staff.closetask', [], false) }}">
                    @csrf
                    <h5 class="card-title">Task Information</h5>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Task Name</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ $taskinfo['name'] }}" disabled readonly />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="remark" class="col-md-4 col-form-label text-md-right">Remark</label>
                        <div class="col-md-6">
                          <textarea rows="5" class="form-control" id="remark" name="remark"
                          disabled readonly>{{ $taskinfo['remark'] }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="ttype" class="col-md-4 col-form-label text-md-right">Task Type</label>
                      <div class="col-md-6">
                        <input id="ttype" type="text" class="form-control" name="ttype" value="{{ $tasktype }}" disabled readonly />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="thours" class="col-md-4 col-form-label text-md-right">Total Hours</label>
                      <div class="col-md-6">
                        <input id="thours" type="text" class="form-control" name="thours" value="{{ $taskinfo['total_hours'] }}" disabled readonly />
                      </div>
                    </div>
                    <div class="form-group row">
                        <label for="addedby" class="col-md-4 col-form-label text-md-right">Added By</label>
                        <div class="col-md-6">
                            <input id="addedby" type="text" class="form-control" name="addedby" value="{{ $taskinfo['created_by'] }}" disabled readonly />
                        </div>
                    </div>
                    <input id="task_id" type="hidden" name="task_id" value="{{ $taskinfo['id'] }}" >
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                          @if($taskinfo['status'] == 1)
                            @if($lock == 'disabled')
                            <button type="button" class="btn btn-warning" >Only owner can add activity</button>
                            @else
                            <a href="{{ route('staff.addact', ['task_id' => $taskinfo['id']], false) }}"><button type="button" class="btn btn-secondary" >Add Activity</button></a>
                            @endif

                            <button type="submit" class="btn btn-warning">Close Task</button>
                          @else
                            <button type="submit" class="btn btn-primary" disabled>Closed</button>
                          @endif
                        </div>
                    </div>
                  </form>
                </div>
                <div class="card-body">
                  <h5 class="card-title">List of Activities</h5>
                  <table id="taskdetailtable" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Type</th>
                        <th scope="col">Remark</th>
                        <th scope="col">Hours</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($activities as $acts)
                      <tr>
                        <td>{{ $acts['date'] }}</td>
                        <td>{{ $acts['act_type_desc'] }}</td>
                        <td>{{ $acts['remark'] }}</td>
                        <td>{{ $acts['hours_spent'] }}</td>
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
