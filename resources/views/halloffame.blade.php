@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">Today's Top Diary Performer</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="taskdetailtable" class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Name</th>
                          <th scope="col">Division</th>
                          <th scope="col">Total Hours</th>
                          <th scope="col">Start Working At</th>
                          <th scope="col">Last Update</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($diarytop10 as $acts)
                        <tr>
                          <td><a href="{{ route('staff', ['staff_id' => $acts->User->id], false) }}">{{ $acts->User->name }}</a></td>
                          <td>{{ $acts->User->unit }}</td>
                          <td>{{ $acts->actual_hours }}</td>
                          <td>{{ $acts->start_working }}</td>
                          <td>{{ $acts->updated_at }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">Top Productivity Since Last 7 Days</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="montop" class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Name</th>
                          <th scope="col">Division</th>
                          <th scope="col">Expected Hours</th>
                          <th scope="col">Actual Hours</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($montop as $acts)
                        <tr>
                          <td><a href="{{ route('staff', ['staff_id' => $acts['id']], false) }}">{{ $acts['name'] }}</a></td>
                          <td>{{ $acts['div'] }}</td>
                          <td>{{ $acts['exp'] }}</td>
                          <td><a href="{{ route('phofs', ['staff_no' => $acts['staff_no']], false) }}">{{ $acts['act'] }}</a></td>
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
