@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col">
          <div class="row">
            <div class="col-lg-6 mb-3">
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
                  @if(isset($superior))
                  Report To : <a href="{{ route('staff', ['staff_id' => $superior->id], false) }}">{{ $superior->name }}</a><br />
                  @endif
                </p>
              </div>
            </div></div><br />
            <div class="col-lg-6 mb-3">
            <div class="card">
              <div class="card-header">Action</div>
              <div class="card-body">
                <!-- <h5 class="card-title">Action</h5> -->

                <div class="row">
                  @if($isvisitor == false)
                  <div class="col-xl-6 mb-3">
                    <a href="{{ route('staff.addact', [], false) }}">
                      <div class="card text-center text-white bg-success">
                        <div class="card-body">
                          <p class="card-text"><i class="fa fa-pencil-square-o"></i> Update Diary</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  @endif
                  @if($user->job_grade == '3')
                  <div class="col-xl-6 mb-3">
                    <a href="{{ route('report.agm.recent', ['agm_id' => $user->id ], false) }}">
                      <div class="card text-center text-white bg-primary">
                        <div class="card-body">
                          <p class="card-text"><i class="fa fa-people"></i> Team Summary</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  @endif
                  <div class="col-xl-6 mb-3">
                    <a href="{{ route('staff.list', ['staff_id' => $staff_id], false) }}">
                      <div class="card text-center text-white bg-info">
                        <div class="card-body">
                          @if($isvisitor == false)
                          <p class="card-text"><i class="fa fa-list-alt"></i> My Monthly Activities</p>
                          @else
                          <p class="card-text"><i class="fa fa-list-alt"></i> Monthly Activities</p>
                          @endif
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-xl-6 mb-3">
                    <a href="{{ route('ps.list', ['staff_id' => $staff_id ], false) }}">
                      <div class="card text-center text-dark bg-warning">
                        <div class="card-body">
                          <p class="card-text"><i class="fa fa-wheelchair-alt"></i> Skill Competency</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-xl-6 mb-3">
                    <a href="{{ route('staff.lochist', ['staff_id' => $staff_id], false) }}">
                      <div class="card text-center text-white bg-secondary">
                        <div class="card-body">
                          @if($isvisitor == false)
                          <p class="card-text"><i class="fa fa-map-marker"></i> Where I've Been</p>
                          @else
                          <p class="card-text"><i class="fa fa-map-marker"></i> Where This Person Has Been</p>
                          @endif
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
          </div>
          <div class="card mb-3">
            <div class="card-header">Summary</div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="card mb-3">
                    <div class="card-header bg-info text-white">Today's Productivity</div>
                    <div class="card-body">
                      <div class="row text-center">
                        <div class="col-4 border-right">
                          <h1 class="card-title">{{ $todaydf->actual_hours }}</h1>
                          <p class="card-text">
                            Total Actual Hours
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
                </div>
                <div class="col-md-6">
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
            <div class="card mb-3">
              <div class="card-header">My Diary Calendar</div>
              <div class="card-body">
                <!-- <h5 class="card-title"></h5> -->
                {!! $cds->calendar() !!}
              </div>
            </div>

            @if(sizeof($subords) > 0)
            <div class="card">
              <div class="card-header">My Subordinate</div>
              <div class="card-body">
                <div class="card-columns">
                  @foreach($subords as $asub)
                  @if($asub['status'] != 0)
                  <div class="card text-center">
                    <a href="{{ route('staff', ['staff_id' => $asub['staff_id']], false) }}">
                    <div class="card-body">
                      <h5 class="card-title">{{ $asub['staff_no'] }}</h5>
                      <p class="card-text">{{ $asub['name'] }}</p>
                    </div>
                    <div class="card-footer text-muted">
                      Yesterday: {{ $asub['yesterday_act'] }} / {{ $asub['yesterday_exp'] }}. Today: {{ $asub['today_act'] }} / {{ $asub['today_exp'] }}
                    </div>
                    </a>
                  </div>
                  @else
                  <div class="card text-center text-white bg-secondary">
                    <div class="card-body">
                      <h5 class="card-title">{{ $asub['staff_no'] }}</h5>
                      <p class="card-text">{{ $asub['name'] }}</p>
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

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
{!! $cds->script() !!}
@endsection
