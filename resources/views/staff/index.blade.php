@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css" />
@endsection

@section('content')
<div class="container">
    <!-- begin row 1 -->
    <div class="row ">

        <div class="col-md-5 ">
            <!-- begin col welcome -->
            <div class="section-header mb-0">
                <h2>
                    WELCOME BACK
                </h2>
            </div>
            <div class="row">

                <div class="col-10 text-justify">
                    We Trust you had a productive day today. Here at Trust update your tasks & manage
                    your team’s overall productivity performance on a daily & weekly basis. We Trust you
                    know your work better than others hence share them here!

                </div>
                <div class="col-2">


                </div>



            </div>
        </div> <!-- end welcome column -->









        <!-- begin col 1 -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    Staff - {{ $user['staff_no'] }}
                </div>


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
Report To : <a href="{{ route('staff', ['staff_id' => $superior->id], false) }}">{{ $superior->name }}</a><br />
@endif</pre>
                        </div>
                        <div class="col-3 p-1">
                            <img class="card-img" style="border: 1px solid #000; max-width:120px; max-height:120px;"
                                src="{{ route('staff.image', ['staff_no' => $user['staff_no']]) }}" alt="staff picture">
                        </div>
                    </div>
                </div>


            </div> <!-- end card -->
        </div><!-- end col 1 -->

    </div> <!-- end row 1 -->
    <!-- begin row 2 -->
    <!--
    <div class="row">
        <div class="col-md-9">
            <div class="section-header mb-0">
                <h2>
                    PENDING UPDATE
                </h2>
            </div>
            <div class="box-zahid">
                <p></p>
            </div>
        </div>
    </div> 
-->
    <!-- end row 2 -->


    <!-- begin row 3 -->
    <div class="row">
        <div class="col-md-12">
            <div class="section-header mb-0">
                <h2>
                    FEATURES
                </h2>
            </div>
            <!-- begin features subrows-->
            <div class="row">


                <div class="col-md-3 col-sm-6 mt-1 p-1">
                    <a href="{{ route('clock.list', ['staff_id' => $staff_id], false) }}">
                        <div class="box-zahid text-center p-3 h-100">
                            <img src="/img/map.png" class="img-fluid float-none h-75">
                            <p>Check in Location</p>
                        </div>
                    </a>
                </div>
                @if($isvisitor == false)
                <div class="col-md-3 col-sm-6 mt-1 p-1">
                    <a href="{{ route('staff.addact', [], false) }}">
                        <div class="box-zahid text-center p-3 h-100">
                            <img src="/img/diary.png" class="img-fluid float-none h-75">
                            <p>Update Diary</p>
                        </div>
                    </a>
                </div>
                @endif
                <div class="col-md-3 col-sm-6 mt-1 p-1">
                    <a href="{{ route('staff.lochist', ['staff_id' => $staff_id], false) }}">
                        <div class="box-zahid text-center p-3 h-100">
                            <img src="/img/desk.png" class="img-fluid float-none h-75">
                            <p>Workspace History</p>
                        </div>
                    </a>
                </div>




                <div class="col-md-3 col-sm-6 mt-1 p-1">
                    <a href="{{ route('ps.list', ['staff_id' => $staff_id ], false) }}">
                        <div class="box-zahid text-center p-3 h-100">
                            <img src="/img/recruitment.png" class="img-fluid float-none h-75">
                            <p>profile + skills</p>
                        </div>
                    </a>
                </div>
                @if($user->job_grade != '5')
                <div class="col-md-3 col-sm-6 mt-1 p-1">
                    <a href="{{ route('report.agm.recent', ['agm_id' => $user->id ], false) }}">
                        <div class="box-zahid text-center p-3 h-100">
                            <img src="/img/browser.png" class="img-fluid float-none">
                            <p>Team Dashboard</p>
                        </div>
                    </a>
                </div>
                @endif
                <div class="col-md-3 col-sm-6 mt-1 p-1">
                    <a href="https://era.tm.com.my/happy-meter">

                        <div class="box-zahid text-center p-3 h-100">
                            <img src="/img/smile.png" class="img-fluid float-none ">
                            <p>SMILE</p>
                        </div>
                    </a>
                </div>
                @if($iscaretaker == true || Auth::user()->role < 2 ) <div class="col-md-3 col-sm-6 mt-1 p-1">
                    <a href="{{ route('mleave.list', ['staff_id' => $staff_id], false) }}">
                        <div class="box-zahid text-center p-3 h-100">
                            <img src="/img/TrustNew2.png" alt="zerorize"
                                class="img-fluid img-thumbnail float-none h-75" />
                            <p>Zerorize</p>
                        </div>
                    </a>
            </div>
            @endif

            <div class="col-md-3 col-sm-6 mt-1 p-1">
                <a href="{{ route('staff.list', ['staff_id' => $staff_id], false) }}">
                    <div class="box-zahid text-center p-3 h-100">
                        <img src="/img/diary_entries.png" alt="zerorize"
                            class="img-fluid img-thumbnail float-none h-75">
                        <p>Diary Entries</p>
                    </div>
                </a>
            </div>
        </div> <!-- end features subrows-->
    </div>
</div> <!-- end row 3 -->


<!-- begin row 4 -->
<div class="row">
    <div class="col-md-6">
        <div class="section-header mb-0">
            <h2>
                SUMMARY
            </h2>
        </div>

        <div class="card-body">
            <div class="row text-center">
                <div class="col-6 border-right">
                    <h1 class="card-title">{{ $todaydf->actual_hours }}</h1>
                    <p class="card-text">
                        ACTUAL HOURS
                    </p>
                </div>
                <!--
                <div class="col-4 border-right">
                    <h1 class="card-title">{{ $todaydf->expected_hours }}</h1>
                    <p class="card-text">
                        Expected Hours
                    </p>
                </div>-->
                <div class="col-6">
                    <h1 class="card-title">{{ $todayperc }}%</h1>
                    <p class="card-text">
                        Productivity
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="section-header mb-0">
            <h2>
                PRODUCTIVITY
            </h2>
        </div>

        <div class="card ">
            {!! $chart->render() !!}
        </div>

    </div>


</div> <!-- end row 4 -->


<!-- begin row 5 -->
<div class="row">
    <div class="col-md-12">
        <div class="section-header mb-0">
            <h2>
                CALENDAR
            </h2>
        </div>
        <div class="box-zahid">
            <div class="card-body">
                {!! $cds->calendar() !!}
                <p class="card-text"><br /> Legend:
                    <button class="btn btn-info">Normal Entry</button>
                    <button class="btn btn-success">Working on non-working day</button>
                    <button class="btn btn-danger">0 hours on expected working day</button>
                    <button class="btn btn-warning">Leave Info</button>
                    <button class="btn btn-secondary">Public Holiday</button>
                </p>
            </div>
        </div>
    </div>
</div> <!-- end row 5 -->





@if(sizeof($subords) > 0)
<!-- begin row 6 -->
<div class="row">
    <div class="col-md-12">
        <div class="section-header mb-0">
            <h2>My Subordinate</h2>
        </div>

        <div class="row">
            @foreach($subords as $asub)
            <div class="col-md-4 col-sm-6 " style="padding:0">
                @if($asub['status'] != 0)
                <div class="card mb-3 text-center" style="padding:0">
                    <a href="{{ route('staff', ['staff_id' => $asub['staff_id']], false) }}">
                        <div class="card-body" style="padding:0">
                            <div class="row">
                                <div class="col-3 p-1 m-0">
                                    <img class="card-img"
                                        style="border: 1px solid #000; max-width:64px; max-height:64px;"
                                        src="{{ route('staff.image', ['staff_no' => $asub['staff_no']]) }}"
                                        alt="gambo staff">
                                </div>
                                <div class="col-9">
                                    <div class="card-title">{{ $asub['staff_no'] }}</div>
                                    <p class="card-text" style="min-height:3em">{{ $asub['name'] }}</p>
                                </div>
                            </div>
                        </div>
                        @if($canseepnc == true)
                        <div class="card-footer text-muted">
                            Yesterday: {{ $asub['yesterday_act'] }} / {{ $asub['yesterday_exp'] }}. Today:
                            {{ $asub['today_act'] }} / {{ $asub['today_exp'] }}
                        </div>
                        @endif
                    </a>
                </div>
                @else
                <div class="card mb-3 text-center text-white bg-secondary">
                    <div class="card-body" style="padding:0">
                        <div class="row">
                            <div class="col-3 p-1">
                                <img class="card-img" style="border: 1px solid #000; max-width:64px; max-height:64px;"
                                    src="{{ route('staff.image', ['staff_no' => $asub['staff_no']]) }}"
                                    alt="staff picture">
                            </div>
                            <div class="col-9 p-1">
                                <div class="card-title">{{ $asub['staff_no'] }}</div>
                                <p class="card-text" style="min-height:3em">{{ $asub['name'] }}</p>
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








</div> <!-- end container -->

@endsection

@if($canseepnc == true)
@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
{!! $cds->script() !!}
@endsection
@endif