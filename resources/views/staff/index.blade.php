@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
              <div class="card-header">Staff Home Page</div>
              <div class="card-body">
                <h5 class="card-title">My Info</h5>
                <p class="card-text text-monospace">
                  Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $user['name'] }}<br />
                  Division&nbsp;: {{ $user['unit'] }}<br />
                  Unit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $user['subunit'] }}<br />
                  Email&nbsp;&nbsp;&nbsp;&nbsp;: {{ $user['email'] }}<br />
                  Mobile&nbsp;&nbsp;&nbsp;: {{ $user['mobile_no'] }}<br />
                  Current Check-in : {{ $currcekin }}<br />
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
                  @if($cuser == $staff_id)
                  <a href="{{ route('staff.addact', [], false) }}" class="list-group-item list-group-item-action">Update Daily Activity</a>
                  @endif
                </div>
              </div>
              <div class="card-body">
                <h5 class="card-title">My Subordinate</h5>
                <div class="card-columns">
                  @foreach($subords as $asub)
                  @if(isset($asub['subordinate_id']))
                  <div class="card text-center text-white bg-dark">
                    <a href="{{ route('staff', ['staff_id' => $asub['subordinate_id']], false) }}">
                    <div class="card-body">
                      <h5 class="card-title">{{ $asub['sub_staff_no'] }}</h5>
                      <p class="card-text">{{ $asub['sub_name'] }}</p>
                    </div>
                    </a>
                  </div>
                  @else
                  <div class="card text-center">
                    <div class="card-body">
                      <h5 class="card-title">{{ $asub['sub_staff_no'] }}</h5>
                      <p class="card-text">{{ $asub['sub_name'] }}</p>
                    </div>
                  </div>
                  @endif
                  @endforeach
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
