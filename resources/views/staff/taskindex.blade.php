@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Registered Task for {{ $staff_name }}</div>
                <div class="card-body">
                  <form method="POST" action="{{ route('house.doreg') }}">
                    @csrf
                    <h5 class="card-title">Add new task</h5>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                        <div class="col-md-6">
                            <input id="name" type="text" name="name" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="remark" class="col-md-4 col-form-label text-md-right">Remark</label>
                        <div class="col-md-6">
                          <textarea rows="5" id="remark" name="remark"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="task" class="col-md-4 col-form-label text-md-right">Task</label>
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
                <div class="card-header"> </div>
                <div class="card-body">
                  <h5 class="card-title">List of tasks</h5>
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
                        <td>{{ $atask['name'] }}</td>
                        <td>{{ $atask['total_hours'] }}</td>
                        <td>{{ $atask['id'] }}</td>
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
