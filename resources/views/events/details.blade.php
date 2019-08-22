@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
              <div class="card-header">Event Details</div>
              <div class="card-body">
                <h5 class="card-title">{{ $eventinfo->event_name }}</h5>
                <p class="card-text">
                  Organized by : {{ $eventinfo->Organizer->name }}<br />
                  Location : {{ $eventinfo->Location->label }} @ {{ $eventinfo->Location->building->floor_name }}<br />
                  From : {{ $eventinfo->start_time }}<br />
                  To : {{ $eventinfo->end_time }}<br />
                </p>
              </div>
            </div><br />
            <div class="card">
                <div class="card-header">List of Attendees</div>
                <div class="card-body">
                  <table id="taskdetailtable" class="table table-striped table-bordered table-responsive table-hover">
                    <thead>
                      <tr>
                        @foreach($headers as $ahead)
                        <th scope="col">{{ $ahead }}</th>
                        @endforeach
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($attendees as $atask)
                      <tr>
                        <td><a title="{{ $atask->staff_no }}" href="{{ route('staff', ['staff_id' => $atask->staff_id], false) }}">{{ $atask->name }}</a></td>
                        <td>{{ $atask->email }}</td>
                        <td>{{ $atask->unit }}</td>
                        @foreach($atask->day_attended as $atteday)
                        <td>
                          @if($atteday == 0)
                          &#10060;
                          @elseif($atteday == 2)
                          &#10068;
                          @else
                          &#9989;
                          @endif
                        </td>
                        @endforeach
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('page-js')
<!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" defer>
$(document).ready(function() {
    $('#taskdetailtable').DataTable({
      responsive: true
    });
} );
</script>
@stop
