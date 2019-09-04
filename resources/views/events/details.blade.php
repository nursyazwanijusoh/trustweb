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
                  Status : {{ $eventinfo->status }}<br />
                  @if($eventinfo->status == 'Rejected')
                  Remark : {{ $eventinfo->admin_remark }}<br />
                  @endif
                </p>
                @if($eventinfo->status == 'Active')
                <div class="form-group row">
                    <div class="col text-center">
                      @if($isowner)
                      <form action="{{ route('area.cancelevent', [], false) }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $eventinfo->id }}" />
                        <button type="submit" class="btn btn-warning">Cancel Event</button>
                      </form>
                      @endif
                      @if($isadmin)
                        <button type="submit" class="btn btn-danger" data-toggle="modal" data-target="#editCfgModal">Reject Event</button>

                      @endif
                    </div>
                </div>
                @endif
              </div>
            </div><br />
            <div class="card">
                <div class="card-header">List of Attendees</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="taskdetailtable" class="table table-striped table-bordered table-hover w-auto">
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
        <div class="modal fade" id="editCfgModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Reject {{ $eventinfo->event_name }}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('area.rejectevent', [], false) }}">
              @csrf
              <div class="modal-body">
                <input type="hidden" value="{{ $eventinfo->id }}" name="id" id="edit-id" />
                <div class="form-group row">
                  <label for="edit-value" class="col-sm-4 col-form-label text-sm-right">Remark</label>
                  <textarea rows="3" class="form-control col-sm-6" id="edit-value" name="remark" required></textarea>

                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Reject Event</button>
              </div>
            </form>
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
