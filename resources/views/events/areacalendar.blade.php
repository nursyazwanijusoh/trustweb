@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Add Event</div>
                <div class="card-body">
                  @if($errors->any())
                  <div class="alert alert-warning" role="alert">{{ $errors->first() }}</div>
                  @endif
                  <form method="POST" action="{{ route('area.addevent', [], false) }}">
                    @csrf
                    <input type="hidden" name="area_id" value="{{ $marea->id }}"  />
                    <div class="form-group row">
                        <label for="event_name" class="col-md-4 col-form-label text-md-right">Event Name</label>
                        <div class="col-md-6">
                            <input id="event_name" class="form-control" type="text" name="event_name"
                              placeholder="Some description for this event" required maxlength="150"
                              value="{{ old('event_name') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col text-center">
                            <input id="fullday" class="form-check-input" type="checkbox" name="fullday" onclick="selFullDay()">
                            <label for="fullday" class="form-check-label">Is Full Day Event</label>
                        </div>
                    </div>
                    <div id="full_day_event">
                      <h5 class="card-title">Full Day Event</h5>
                      <div class="form-group row">
                          <label for="fstartdate" class="col-md-4 col-form-label text-md-right">Start Date</label>
                          <div class="col-md-6">
                            <input type="date" name="fstartdate" id="fstartdate" value="{{ $curdate }}" onchange="validateStartDate()"/>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="fenddate" class="col-md-4 col-form-label text-md-right">End Date</label>
                          <div class="col-md-6">
                            <input type="date" name="fenddate" id="fenddate" value="{{ $curdate }}" onchange="validateEndDate()"/>
                          </div>
                      </div>
                    </div>
                    <div id="hourly_event">
                      <h5 class="card-title">Partial Day Event</h5>
                      <div class="form-group row">
                          <label for="pevdate" class="col-md-4 col-form-label text-md-right">Event Date</label>
                          <div class="col-md-6">
                            <input type="date" name="pevdate" id="pevdate" value="{{ $curdate }}" />
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="pstime" class="col-md-4 col-form-label text-md-right">Start Time</label>
                          <div class="col-md-6">
                            <input type="time" name="pstime" id="pstime" value="{{ $stime }}" />
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="petime" class="col-md-4 col-form-label text-md-right">End Time</label>
                          <div class="col-md-6">
                            <input type="time" name="petime" id="petime" value="{{ $etime }}"/>
                          </div>
                      </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Register Event</button>
                        </div>
                    </div>
                  </form>
                </div>
            </div><br />
            <div class="card">
                <div class="card-header">Events for {{ $marea->label }}</div>
                <div class="card-body">
                  {!! $cds->calendar() !!}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('page-js')
<script type="text/javascript" defer>
$(document).ready(function() {
    document.getElementById("full_day_event").style.display = "none";
    document.getElementById("fullday").checked = false;

    document.getElementById("fstartdate").required = false;
    document.getElementById("fenddate").required = false;
    document.getElementById("pevdate").required = true;
    document.getElementById("pstime").required = true;
    document.getElementById("petime").required = true;
} );

function selFullDay(){
  if(document.getElementById("fullday").checked == true){
    document.getElementById("full_day_event").style.display = "block";
    document.getElementById("hourly_event").style.display = "none";

    document.getElementById("fstartdate").required = true;
    document.getElementById("fenddate").required = true;
    document.getElementById("pevdate").required = false;
    document.getElementById("pstime").required = false;
    document.getElementById("petime").required = false;
  } else {
    document.getElementById("full_day_event").style.display = "none";
    document.getElementById("hourly_event").style.display = "block";

    document.getElementById("fstartdate").required = false;
    document.getElementById("fenddate").required = false;
    document.getElementById("pevdate").required = true;
    document.getElementById("pstime").required = true;
    document.getElementById("petime").required = true;
  }
}

function validateEndDate(){
  var sdate = document.getElementById("fstartdate").valueAsDate;
  var edate = document.getElementById("fenddate").valueAsDate;

  if(edate < sdate){
    document.getElementById("fstartdate").valueAsDate = edate;
  }

}

function validateStartDate(){
  var sdate = document.getElementById("fstartdate").valueAsDate;
  var edate = document.getElementById("fenddate").valueAsDate;

  if(edate < sdate){
    document.getElementById("fenddate").valueAsDate = sdate;
  }

}


</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
{!! $cds->script() !!}

@stop
