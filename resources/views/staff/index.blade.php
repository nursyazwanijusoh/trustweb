@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
              <div class="card-header">Staff Home Page</div>
              <div class="card-body">
                <h5 class="card-title">My Info</h5>
                <p class="card-text">
                  Name: {{ $user['name'] }}<br />
                  Division: {{ $user['unit'] }}<br />
                  Unit: {{ $user['subunit'] }}<br />
                  Email: {{ $user['email'] }}<br />
                  Mobile: {{ $user['mobile_no'] }}<br />
                </p>
              </div>
              <div class="card-body">
                <h5 class="card-title">Summary</h5>
                <div class="list-group">
                  <li class="list-group-item list-group-item-info">{{ $opentask }} Open Task</li>
                  <li class="list-group-item list-group-item-success">{{ $donetask }} Completed Task</li>
                </div>
              </div>
              <div class="card-body">
                <h5 class="card-title">Action</h5>
                <div class="list-group">
                  <a href="{{ route('staff.t', ['staff_id' => $staff_id], false) }}" class="list-group-item list-group-item-action">Task Management</a>
                  <a href="{{ route('staff.addact', [], false) }}" class="list-group-item list-group-item-action">Update Daily Activity</a>
                </div>
              </div>
              <div class="card-body">
                <h5 class="card-title">My Subordinate</h5>
                <div class="card-columns">
                  @foreach($subords as $asub)
                  <div class="card text-center">
                    <div class="card-body">
                      <h5 class="card-title">{{ $asub['sub_staff_no'] }}</h5>
                      <p class="card-text">{{ $asub['sub_name'] }}</p>
                      @if(isset($asub['subordinate_id']))
                      <p class="card-text">
                        <a href="{{ route('staff', ['staff_id' => $asub['subordinate_id']], false) }}">Active</a></p>
                      @else
                      <p class="card-text"><small class="text-muted">Inactive</small></p>
                      @endif
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
