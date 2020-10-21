@extends('layouts.app')

@section('title', 'User Dashboard : ' . $user['staff_no'])

@section('page-css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col">
          <div class="row">
            @if($canseepnc == true)
            <div class="col-md-6 mb-3">
            @else
            <div class="col-md-12 mb-3">
            @endif
            <div class="card">
              <div class="card-header">Staff Home Page - {{ $user['staff_no'] }}</div>
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
@if(isset($superior))
Report To : <a href="{{ route('staff', ['staff_id' => $superior->id], false) }}">{{ $superior->name }}</a>
@endif
</pre>
                  </div>
                  <div class="col-3 p-1">
                    <img class="card-img"  style="border: 1px solid #000; max-width:120px; max-height:120px;" src="{{ route('staff.image', ['staff_no' => $user['staff_no']]) }}" alt="gambo staff">
                  </div>
                </div>
                <!-- div class="row">
                  <div class="col p-1">
                    <p class="card-text text-monospace">

                      @if(isset($user->LastLocation))
                      Last Location :<a href="https://www.google.com/maps/search/?api=1&query={{ $user->LastLocation->latitude . ',' . $user->LastLocation->longitude }}" target="_blank">
                        @if(isset($user->LastLocation->address))
                        {{ $user->LastLocation->address }}
                        @else
                        See in map
                        @endif
                      </a> at {{ $user->LastLocation->created_at }}<br />
                      @endif
                    </p>
                  </div>
                </div -->

              </div>
            </div></div>
            @if($canseepnc == true || $iscaretaker == true)
            <div class="col-md-6 mb-3">
            <div class="card">
              <div class="card-header">Action</div>
              <div class="card-body">
                <!-- <h5 class="card-title">Action</h5> -->

                <div class="row">
                  <div class="col-6 col-xl-4 mb-1 p-1">
                    <a href="{{ route('clock.list', ['staff_id' => $staff_id], false) }}">
                      <div class="card text-center text-white bg-dark">
                        <div class="card-body">
                          <p class="card-text"><i class="fa fa-street-view"></i> Check-in Location</p>
                        </div>
                      </div>
                    </a>
                  </div>

                  <div class="col-6 col-xl-4 mb-1 p-1">
                    <a href="{{ route('staff.lochist', ['staff_id' => $staff_id], false) }}">
                      <div class="card text-center text-white bg-secondary">
                        <div class="card-body">
                          <p class="card-text"><i class="fa fa-building-o"></i> Workspace History</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  @if($isvisitor == false)
                  <div class="col-6 col-xl-4 mb-1 p-1">
                    <a href="{{ route('staff.addact', [], false) }}">
                      <div class="card text-center text-white bg-success">
                        <div class="card-body">
                          <p class="card-text"><i class="fa fa-pencil-square-o"></i> Update Diary</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  @endif
                  @if($user->job_grade != '5')
                  <div class="col-6 col-xl-4 mb-1 p-1">
                    <a href="{{ route('report.agm.recent', ['agm_id' => $user->id ], false) }}">
                      <div class="card text-center text-white bg-primary">
                        <div class="card-body">
                          <p class="card-text"><i class="fa fa-users"></i> Team Productivity</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-6 col-xl-4 mb-1 p-1">
                    <a href="{{ route('report.team.locations', ['uid' => $user->id ], false) }}">
                      <div class="card text-center text-dark bg-light">
                        <div class="card-body">
                          <p class="card-text"><i class="fa fa-map"></i> Team Whereabout</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-6 col-xl-4 mb-1 p-1" title="Since you asked for it">
                    <a href="{{ route('reports.c.detail', ['uid' => $user->id ], false) }}">
                      <div class="card text-center text-dark bg-secondary">
                        <div class="card-body">
                          <p class="card-text"><i class="fa fa-calendar-check-o"></i> Team Check-in Details</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  @endif
                  <div class="col-6 col-xl-4 mb-1 p-1">
                    <a href="{{ route('ps.list', ['staff_id' => $staff_id ], false) }}">
                      <div class="card text-center text-dark bg-warning">
                        <div class="card-body">
                          <p class="card-text"><img src="/img/competency.png" height="14em" width="14em" alt="" title="" /> Skill Competency</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  <!-- <div class="col-6 col-xl-4 mb-1 p-1">
                    <a href="{{ route('ps.exps', ['staff_id' => $staff_id ], false) }}">
                      <div class="card text-center text-dark bg-light">
                        <div class="card-body">
                          <p class="card-text"><i class="fa fa-podcast"></i> Experiences</p>
                        </div>
                      </div>
                    </a>
                  </div> -->
                  <div class="col-6 col-xl-4 mb-1 p-1">
                    <a href="{{ route('staff.list', ['staff_id' => $staff_id], false) }}">
                      <div class="card text-center text-white bg-info">
                        <div class="card-body">
                          <p class="card-text"><i class="fa fa-list-alt"></i> Diary Entries</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  @if($iscaretaker == true || Auth::user()->role < 2 )
                  <div class="col-6 col-xl-4 mb-1 p-1">
                    <a href="{{ route('mleave.list', ['staff_id' => $staff_id], false) }}">
                      <div class="card text-center text-white bg-danger">
                        <div class="card-body">
                          <p class="card-text"><i class="fa fa-calendar"></i> Zerorize Expected Hours</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  @endif
                  <div class="col-6 col-xl-4 mb-1 p-1">
                    <a href="{{ route('smile', [], false) }}">
                      <div class="card text-center text-white bg-primary">
                        <div class="card-body">
                          <p class="card-text"><img src="/images/smile/floaticon.png"  height="23em" width="23em" alt="" title="" /> Smile</p>
                        </div>
                      </div>
                    </a>
                  </div>

                  <!-- <div class="col-sm-4 mb-3">
                    <a href="{{ route('area.myevents', ['id' => $staff_id], false) }}">
                      <div class="card text-center text-white bg-secondary">
                        <div class="card-body">
                          <p class="card-text"><i class="fa fa-user-times"></i> My Events</p>
                        </div>
                      </div>
                    </a>
                  </div> -->
                </div>
              </div>
            </div></div>
            @endif
          </div>
          <div class="card mb-3">
            <div class="card-header">Summary</div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-6">
                  <div class="card mb-3" title="{{ $todaytitle }}">
                    <div class="card-header bg-{{ $todaycol }} text-white">Today's Productivity</div>
                    <div class="card-body">
                      <div class="row text-center">
                        <div class="col-4 border-right">
                          <h1 class="card-title">{{ $todaydf->actual_hours }}</h1>
                          <p class="card-text">
                            Actual Hours
                          </p>
                        </div>
                        <div class="col-4 border-right">
                          <h1 class="card-title">{{ $todaydf->expected_hours }}</h1>
                          <p class="card-text">
                            Expected Hours
                          </p>
                        </div>
                        <div class="col-4">
                          <h1 class="card-title">{{ $todayperc }}%</h1>
                          <p class="card-text">
                            Productivity
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card mb-3" title="{{ $weektitle }}">
                    <a title="Click for infographic" href="{{ route('phofs', ['staff_no' => $user['staff_no'], 'date' => $ldate->subDay()->toDateString()], false) }}">
                      <div class="card-header bg-{{ $weekcol }} text-white">Past 7 Days ( {{ $cdate->toDateString() }} to {{ $ldate->toDateString() }} )</div>
                    </a>
                    <div class="card-body">
                      <div class="row text-center">
                        <div class="col-4 border-right">
                          <h1 class="card-title">{{ number_format($weekact, 1) }}</h1>
                          <p class="card-text">
                            Total Actual Hours
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
                <div class="col-lg-6">
                  <div class="card ">
                    {!! $chart->render() !!}
                  </div>
                </div>
              </div>
              <!-- <h5 class="card-title"></h5> -->
              <!-- <div class="table-responsive text-center"> -->

              <!-- </div> -->

            </div>
          </div>
          @if($canseepnc == true)
          <div class="card mb-3">
            <div class="card-header">My Diary Calendar</div>
            <div class="card-body">
              {!! $cds->calendar() !!}
              <p class="card-text"><br/> Legend:
                <button class="btn btn-info">Normal Entry</button>
                <button class="btn btn-success">Working on non-working day</button>
                <button class="btn btn-danger">0 hours on expected working day</button>
                <button class="btn btn-warning">Leave Info</button>
                <button class="btn btn-secondary">Public Holiday</button>
              </p>
            </div>
          </div>
          @endif

            @if(sizeof($subords) > 0)
            <div class="card">
              <div class="card-header">My Subordinate</div>
              <div class="card-body">
                <div class="row">
                  @foreach($subords as $asub)
                  <div class="col-md-4 col-sm-6">
                  @if($asub['status'] != 0)
                  <div class="card mb-3 text-center">
                    <a href="{{ route('staff', ['staff_id' => $asub['staff_id']], false) }}">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-3 p-1">
                          <img class="card-img"  style="border: 1px solid #000; max-width:64px; max-height:64px;" src="{{ route('staff.image', ['staff_no' => $asub['staff_no']]) }}" alt="gambo staff">
                        </div>
                        <div class="col-9 p-1">
                          <h5 class="card-title">{{ $asub['staff_no'] }}</h5>
                          <p class="card-text">{{ $asub['name'] }}</p>
                        </div>
                      </div>
                    </div>
                    @if($canseepnc == true)
                    <div class="card-footer text-muted">
                      Yesterday: {{ $asub['yesterday_act'] }} / {{ $asub['yesterday_exp'] }}. Today: {{ $asub['today_act'] }} / {{ $asub['today_exp'] }}
                    </div>
                    @endif
                    </a>
                  </div>
                  @else
                  <div class="card mb-3 text-center text-white bg-secondary">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-3 p-1">
                          <img class="card-img"  style="border: 1px solid #000; max-width:64px; max-height:64px;" src="{{ route('staff.image', ['staff_no' => $asub['staff_no']]) }}" alt="gambo staff">
                        </div>
                        <div class="col-9 p-1">
                          <h5 class="card-title">{{ $asub['staff_no'] }}</h5>
                          <p class="card-text">{{ $asub['name'] }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endif
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@if($canseepnc == true)
@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
{!! $cds->script() !!}
@endsection
@endif
