@extends('layouts.app')

@section('title', 'Weekly Infographic : ' . $user['staff_no'] . ' : ' . $edate)

@section('page-css')
<link href="/css/custom.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card bg-light mb-3">
          <div class="card-body">
            <div class="row">
              <div class="col-9 p-1">
<pre class="mb-0">
Name     : {{ $user['name'] }}
Division : {{ $user['unit'] }}
Unit     : {{ $user['subunit'] }}
Position : {{ $user['position'] }}
Email    : {{ $user['email'] }}
Mobile   : {{ $user['mobile_no'] }}
@if(isset($user->report_to))
Report To : <a href="{{ route('staff', ['staff_id' => $user->Boss->id], false) }}">{{ $user->Boss->name }}</a>
@endif
</pre>
              </div>
              <div class="col-3 p-1">
                <img class="card-img float-right"  style="border: 1px solid #000; max-width:120px; max-height:120px;" src="{{ route('staff.image', ['staff_no' => $user['staff_no']]) }}" alt="gambo staff">
              </div>
            </div>
          </div>
          <div class="card-footer">
            Diary Infographic from {{ $sdate }} to {{ $edate }}
          </div>
        </div>
      </div>


      <div class="col-md-6 col-xl-4">
        <!-- daily numbers -->
        <div class="card bg-light mb-3">
          <div class="card-header bg-success text-light">Summary</div>
          <div class="card-body">
            <div class="row text-center">
              <div class="col-4 border-right">
                <h1 class="card-title">{{ number_format($weekact, 1) }}</h1>
                <p class="card-text">
                  Total Hours
                </p>
              </div>
              <div class="col-4 border-right">
                <h1 class="card-title">{{ number_format($weekexp, 1) }}</h1>
                <p class="card-text">
                  Expected Hours
                </p>
              </div>
              <div class="col-4">
                <h1 class="card-title">{{ $weekperc }}%</h1>
                <p class="card-text">
                  Productivity
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-xl-4">
        <!-- Activity Tag -->
        <div class="card bg-light mb-3">
          <div class="card-header bg-info text-light">Hours by Activity Tag</div>
          <div class="card-body">
            {!! $bbtpie->render() !!}
          </div>
        </div>
      </div>
      <div class="col-md-6 col-xl-4">
        <!-- Activity Type -->
        <div class="card bg-light mb-3">
          <div class="card-header bg-info text-light">Hours by Activity Type</div>
          <div class="card-body">
            {!! $pptypepie->render() !!}
          </div>
        </div>
      </div>
      <div class="col-md-6 col-xl-4">
        <!-- daily graph -->
        <div class="card bg-light mb-3">
          <div class="card-header bg-primary text-light">Daily Working Hours</div>
          <div class="card-body">
            {!! $evsag->render() !!}
          </div>
        </div>
      </div>
      <div class="col-lg-12 col-xl-8">
        <!-- Activity Type -->
        <div class="card bg-light mb-3">
          <div class="card-header bg-dark text-light">Activity Breakdown</div>
          <div class="card-body">
            {!! $tvtgraph->render() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
@endsection
