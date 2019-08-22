@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
              <div class="card-header">Staff Home Page</div>
              <div class="card-body">
                <h5 class="card-title">My Information</h5>
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
                <h5 class="card-title">Current Month Activities</h5>
                {!! $chart->render() !!}
              </div>
              <div class="card-body">
                <h5 class="card-title">Action</h5>
                <div class="list-group">
                  <a href="{{ route('staff.lochist', ['staff_id' => $staff_id], false) }}" class="list-group-item list-group-item-action">Where I've Been</a>
                  <!-- <a href="{{ route('ps.list', ['staff_id' => $staff_id], false) }}" class="list-group-item list-group-item-action">Personal Skillset</a> -->
                  <a href="{{ route('staff.list', ['staff_id' => $staff_id], false) }}" class="list-group-item list-group-item-action">My Monthly Activities</a>
                  @if($cuser == $staff_id)
                  <a href="{{ route('staff.addact', [], false) }}" class="list-group-item list-group-item-action">Update Daily Activity</a>
                  @endif
                  <a href="{{ route('area.myevents', ['id' => $staff_id], false) }}" class="list-group-item list-group-item-action">My Events</a>
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
