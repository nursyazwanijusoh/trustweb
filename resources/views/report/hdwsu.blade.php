@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Workspace Occupancies?</div>
                <div class="card-body">
                  <form method="GET" action="{{ route('hdreports.wsu', [], false) }}" id="wsuform">
                    <!-- @csrf -->
                    <!-- <h5 class="card-title">Date range</h5> -->
                    <div class="form-group row">
                      <label for="build" class="col-md-4 col-form-label text-md-right">Floor</label>
                      <div class="col-md-6">
                        <select class="form-control" id="build" name="build_id" onchange="event.preventDefault();
                                      document.getElementById('wsuform').submit();">
                          @foreach ($buildlist as $atask)
                          <option value="{{ $atask->id }}" >{{ $atask->floor_name . '@' . $atask->building_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              @if(isset($buildname))
              <br />
              <div class="card">
                <div class="card-header">Occupied Seats for {{ $buildname }} - {{ $occcount }}</div>
                <div class="card-body">
                  <table class="table table-striped table-bordered table-hover" id="hdwsutable">
                    <thead>
                      <tr>
                        <th scope="col">Staff ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Seat Label</th>
                        <th scope="col">Check-In / Reserve Expire</th>
                        <th scope="col">Type</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($occupied as $atask)
                      <tr>
                        <td>{{ $atask->staff_no }}</td>
                        <td>{{ $atask->name }}</td>
                        <td>{{ $atask->label }}</td>
                        <td>{{ $atask->cin_time }}</td>
                        <td>{{ $atask->User->divName() }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th scope="col">Staff ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Seat Label</th>
                        <th scope="col">Check-In / Reserve Expire</th>
                        <th scope="col">Division</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div><br />

              <div class="card">
                <div class="card-header">Occupied Areas for {{ $buildname }}</div>
                <div class="card-body">
                @foreach($meetarea as $fs)
                  <h5 class="card-title">{{ $fs->label }}</h5>
                  <table class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Division</th>
                        <th scope="col">Check-in Time</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($fs->checkin as $onecekin)
                      <tr>
                        <td>{{ $onecekin->User->name }}</td>
                        <td>{{ $onecekin->User->divName() }}</td>
                        <td>{{ $onecekin->checkin_time }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  <br />
                @endforeach
                </div>
              </div>
              @endif
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
    $('#hdwsutable').DataTable();
} );
</script>
@stop
