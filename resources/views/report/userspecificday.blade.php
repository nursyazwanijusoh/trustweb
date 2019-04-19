@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Activities for {{ $name }} on {{ $date }}</div>
                <div class="card-body">
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Task</th>
                        <th scope="col">Activity</th>
                        <th scope="col">Hours Spent</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $acts)
                      <tr>
                        <td style="white-space:nowrap;"><a href="{{ route('staff.tdetail', ['task_id' => $acts['id']], false) }}">{{ $acts->name }}</a></td>
                        <td>{{ $acts->remark }}</td>
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
@endsection
