@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Registered Task for {{ $staff_name }}</div>
                <div class="card-body">
                  <form method="POST" action="{{ route('staff.addtask', [], false) }}">
                    @csrf
                    <h5 class="card-title">Add new task</h5>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Task Name</label>
                        <div class="col-md-6">
                            <input id="name" class="form-control" type="text" name="name" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="remark" class="col-md-4 col-form-label text-md-right">Remark</label>
                        <div class="col-md-6">
                          <textarea rows="5" class="form-control" id="remark" name="remark" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="task" class="col-md-4 col-form-label text-md-right">Task Type</label>
                      <div class="col-md-6">
                        <select class="form-control" id="task" name="task">
                          @foreach($tasktype as $task)
                          <option value="{{ $task['id'] }}" title="{{ $task['remark'] }}">{{ $task['descr'] }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <input id="s_staff_id" type="hidden" name="s_staff_id" value="{{ $s_staff_id }}" >
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Add Task</button>
                        </div>
                    </div>
                  </form>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Current tasks</h5>
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Task Name</th>
                        <th scope="col">Total Hours</th>
                        <th scope="col">Details</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($currtasklist as $atask)
                      <tr>
                        <th scope="row"><a href="{{ route('staff.tdetail', ['task_id' => $atask['id']], false)}}">{{ $atask['name'] }}</a></th>
                        <td>{{ $atask['total_hours'] }}</td>
                        <td>{{ $atask['remark'] }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Completed tasks</h5>
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Task Name</th>
                        <th scope="col">Total Hours</th>
                        <th scope="col">Details</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($completedtasklist as $ctask)
                      <tr>
                        <th scope="row">
                          <form method="post" action="{{ route('staff.tdetail', [], false)}}">
                            <input type="hidden" name="task_id" value="$ctask['id']"  />
                            <button type="submit" class="btn-link">{{ $ctask['name'] }}</button>
                          </form>
                        </th>
                        <td>{{ $ctask['total_hours'] }}</td>
                        <td>{{ $ctask['remark'] }}</td>
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
