@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css" />
@endsection

@section('content')
<div class="container smaller20">
    <!-- begin row 1 -->
    <div class="row">
        <div class="col-md-6">
            <div class="section-header mb-0">
                <h2>
                    STAFF PROFILE
                </h2>

            </div>
            <div class="box-zahid">
                <p></p>
            </div>

        </div>
        <div class="col-md-6">
            <div class="section-header mb-0">
                <h2>
                    WELCOME BACK
                </h2>
            </div>
            <div class="box-zahid">
                <p></p>
            </div>
        </div>
    </div> <!-- end row 1 -->
    <!-- begin row 2 -->
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
    </div> <!-- end row 2 -->


    <!-- begin row 3 -->
    <div class="row">
        <div class="col-md-12">
            <div class="section-header mb-0">
                <h2>
                    FEATURES
                </h2>
            </div>
            <div class="row">
                <!-- begin features subrows-->

                <div class="col-md-3 mt-1">
                    <div class="box-zahid">
                        <p></p>
                    </div>
                </div>
                <div class="col-md-3 mt-1">
                    <div class="box-zahid">
                        <p></p>
                    </div>
                </div>
                <div class="col-md-3 mt-1">
                    <div class="box-zahid">
                        <p></p>
                    </div>
                </div>
                <div class="col-md-3 mt-1">
                    <div class="box-zahid">
                        <p></p>
                    </div>
                </div>
                <div class="col-md-3 mt-1">
                    <div class="box-zahid">
                        <p></p>
                    </div>
                </div>
                <div class="col-md-3 mt-1">
                    <div class="box-zahid">
                        <p></p>
                    </div>
                </div>
                <div class="col-md-3 mt-1">
                    <div class="box-zahid">
                        <p></p>
                    </div>
                </div>
            </div> <!-- end features subrows-->
        </div>
    </div> <!-- end row 3 -->


    <!-- begin row 4 -->
    <div class="row">
        <div class="col-md-3">
            <div class="section-header mb-0 smaller20">
                <h3>
                    Actual Hours
                </h3>
            </div>
            <div class="box-zahid">
                <p></p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="section-header mb-0">
                <h3>
                    Productivity
                </h3>
            </div>
            <div class="box-zahid">
                <p></p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="section-header mb-0">
                <h2>
                    Graph
                </h2>
            </div>
            <div class="box-zahid">
                <p></p>
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
                <p></p>
            </div>
        </div>
    </div> <!-- end row 5 -->




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