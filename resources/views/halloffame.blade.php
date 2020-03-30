@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">Today's Top Diary Performer</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="taskdetailtable" class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Name</th>
                          <th scope="col">Division</th>
                          <th scope="col">Total Hours</th>
                          <th scope="col">Last Update</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($diarytop10 as $acts)
                        <tr>
                          <td><a href="{{ route('staff', ['staff_id' => $acts->User->id], false) }}">{{ $acts->User->name }}</a></td>
                          <td>{{ $acts->User->unit }}</td>
                          <td><a href="{{ route('staff.addact', ['dfid' => $acts->id], false) }}">{{ $acts->actual_hours }}</a></td>
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
</div>
@endsection
