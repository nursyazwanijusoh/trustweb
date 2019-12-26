@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
              <div class="card-header">Staff Home Page - {{ $user['staff_no'] }}</div>
              <div class="card-body">
                <h5 class="card-title">My Information</h5>
                <p class="card-text text-monospace">
                  Name : {{ $user['name'] }}<br />
                  Division : {{ $user['unit'] }}<br />
                  Unit : {{ $user['subunit'] }}<br />
                  Email : {{ $user['email'] }}<br />
                  Mobile : {{ $user['mobile_no'] }}<br />
                  Current Check-in : {{ $currcekin }}<br />
                </p>
              </div>
            </div><br />
            <div class="card">
              <div class="card-header">Action</div>
              <div class="card-body">
                <!-- <h5 class="card-title">Action</h5> -->

                <div class="row">
                  <div class="col-sm-4 mb-3">
                    <a href="{{ route('staff.addact', [], false) }}">
                      <div class="card text-center text-white bg-success">
                        <div class="card-body">
                          <p class="card-text">Update Diary</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-sm-4 mb-3">
                    <a href="{{ route('staff.list', ['staff_id' => $staff_id], false) }}">
                      <div class="card text-center text-white bg-info">
                        <div class="card-body">
                          <p class="card-text">My Monthly Activities</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-sm-4 mb-3">
                    <a href="{{ route('staff.lochist', ['staff_id' => $staff_id], false) }}">
                      <div class="card text-center text-white bg-info">
                        <div class="card-body">
                          <p class="card-text">Where I've Been</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-sm-4 mb-3">
                    <a href="{{ route('area.myevents', ['id' => $staff_id], false) }}">
                      <div class="card text-center text-white bg-secondary">
                        <div class="card-body">
                          <p class="card-text">My Events</p>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </div><br />
            <div class="card">
              <div class="card-header">Summary</div>
              <div class="card-body">
                <!-- <h5 class="card-title"></h5> -->
                {!! $chart->render() !!}
              </div>
            </div><br />
            @if($subords->count() > 0)
            <div class="card">
              <div class="card-header">My Subordinate</div>
              <div class="card-body">
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
            @endif
        </div>
    </div>
</div>
@endsection
