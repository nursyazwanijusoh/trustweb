@extends('layouts.app')

@section('page-css')
<style>
ul.timeline {
    list-style-type: none;
    position: relative;
    padding-left: 1.5rem;
}

 /* Timeline vertical line */
ul.timeline:before {
    content: ' ';
    background: #f0f;
    display: inline-block;
    position: absolute;
    left: 16px;
    width: 4px;
    height: 100%;
    z-index: 400;
    border-radius: 1rem;
}

li.timeline-item {
    margin: 20px 0;
}

/* Timeline item arrow */
.timeline-arrow {
    border-top: 0.5rem solid transparent;
    border-right: 0.5rem solid #cffcdb;
    border-bottom: 0.5rem solid transparent;
    display: block;
    position: absolute;
    left: 2rem;
}

/* Timeline item circle marker */
li.timeline-item::before {
    content: ' ';
    background: #ddd;
    display: inline-block;
    position: absolute;
    border-radius: 50%;
    border: 3px solid #f00;
    left: 11px;
    width: 14px;
    height: 14px;
    z-index: 400;
    box-shadow: 0 0 5px rgba(254, 0, 0, 0.2);
}

li.tlbg {
    background: #cffcdb;
    background: -webkit-linear-gradient(to right, #cffcdb, #f5d5ee);
    background: linear-gradient(to right, #cffcdb, #f5d5ee);
}


</style>
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">

      @if($isvisitor == false)
      <div class="col-12">
        <div class="card mb-3">
            <div class="card-header">Clock-In (prototype. please use the trUSt mobile app instead)</div>
            <div class="card-body">
              @if (session()->has('alert'))
              <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>{{ session()->get('alert') }}</strong>
              </div>
              @endif
              <form method="POST" action="{{ route('clock.in', [], false) }}">
                @csrf
                <div class="form-group row">
                    <label for="lat" class="col-md-4 col-form-label text-md-right">Latitude</label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="lat" id="lat" placeholder="Location is" maxlength="300" readonly/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="lon" class="col-md-4 col-form-label text-md-right">Longitude</label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="long" id="lon" placeholder="not enabled" maxlength="300" readonly/>
                    </div>
                </div>
                <div id="batens" class="form-group hidden row mb-0">
                    <div class="col text-center">
                      @if(isset($user->curr_attendance))
                      <button type="submit" class="btn btn-success" name="action" value="updateloc" title="Bagitau boss skrg kat mana">Update FlexiSpace</button>
                      <button type="submit" class="btn btn-warning" name="action" value="clockout" title="Keluar">Check-in for FlexiSpace</button>
                      @else
                      <button type="submit" class="btn btn-primary" name="action" value="clockin" title="Masuk">Check-out for FlexiSpace</button>
                      @endif
                    </div>
                </div>
                <input type="hidden" name="staff_id" value="{{ $user->id }}" />
              </form>
            </div>
        </div>
      </div>
      @endif
      <div class="col-12">
        <div class="card bg-light mb-3">
          <div class="card-header">Recent 'Transactions'</div>
          <div class="card-body">
            <ul class="timeline">
              @foreach($lochist as $psh)
              <li class="timeline-item rounded ml-3 p-3 shadow tlbg">
                  <div class="timeline-arrow"></div>
                  <h2 class="h5 mb-0">{!! $psh->action !!}. <a href="https://www.google.com/maps/search/?api=1&query={{ $psh->latitude . ',' . $psh->longitude }}" target="_blank" class="text-success">See in map</a></h2>
                  <span class="small text-secondary"><i class="fa fa-clock-o mr-1"></i>{{ $psh->created_at }}</span>
                  @if(isset($psh->note))
                  <p class="text-small mt-2 font-weight-light">{{ $psh->note }}</p>
                  @endif
                  @if(isset($psh->address))
                  <p class="text-small mt-2 font-weight-light">Location: {{ $psh->address }}</p>
                  @endif
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@section('page-js')
<script type="text/javascript">

function getLocation() {
  if (navigator.geolocation) {

    navigator.geolocation.getCurrentPosition(showPosition, showError);

  } else {
    alert('Location not supported');

    document.getElementById('lat').value = "";
    document.getElementById('lon').value = "";
    document.getElementById('batens').classList.add('d-none');
  }
}

function showError(error) {
  switch(error.code) {
    case error.PERMISSION_DENIED:
      document.getElementById('lon').placeholder = "denied";
      break;
    case error.POSITION_UNAVAILABLE:
      document.getElementById('lon').placeholder = "unavailable. Tried using chrome?";
      break;
    case error.TIMEOUT:
      document.getElementById('lon').placeholder = "not available - timed out";
      break;
    case error.UNKNOWN_ERROR:
      document.getElementById('lon').placeholder = ".. error?";
      break;
  }

  document.getElementById('lat').value = "";
  document.getElementById('lon').value = "";
  document.getElementById('batens').classList.add('d-none');
}

function showPosition(position) {
  document.getElementById('lat').value = position.coords.latitude;
  document.getElementById('lon').value = position.coords.longitude;

}

$(document).ready(function() {
  getLocation();
} );

</script>
@endsection
