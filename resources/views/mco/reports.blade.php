@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center no-gutters">
          <div class="col-xl-12">
            <div class="card mb-3">
              <div class="card-header">MCO Travel - Search</div>
              <div class="card-body">
                <form method="GET" action="{{ route('mco.rpt') }}">
                  @csrf
                  <div class="form-group row">
                      <label for="actdate" class="col-md-3 col-form-label text-md-right">From</label>
                      <div class="col-md-6">
                        <input type="date" class="form-control" name="fromdate" value="{{ $mindate }}" />
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="actdate" class="col-md-3 col-form-label text-md-right">To</label>
                      <div class="col-md-6">
                        <input type="date" class="form-control" name="todate" value="{{ $maxdate }}" />
                      </div>
                  </div>
                  <div class="form-group row mb-0 justify-content-center">
                    <button id="sbtn" type="submit" class="btn btn-primary m-1">Search</button>
                  </div>
                </form>
              </div>
            </div>
        </div>
        <div class="col-xl-12">
          <div class="card mb-3">
            <div class="card-header">Search Result</div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="tapproved" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th scope="col">Date</th>
                      <th scope="col">Name</th>
                      <th scope="col">Division</th>
                      <th scope="col">Unit</th>
                      <th scope="col">Approver</th>
                      <th scope="col">Location</th>
                      <th scope="col">Reason</th>
                      <th scope="col">Check-ins</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($lust as $p)
                    <tr>
                      <td>{{ $p->request_date }}</td>
                      <td><a href="{{ route('staff', ['staff_id' => $p->requestor_id]) }}">{{ $p->requestor->name }}</a></td>
                      <td>{{ $p->requestor->unit }}</td>
                      <td>{{ $p->requestor->subunit }}</td>
                      <td><a href="{{ route('staff', ['staff_id' => $p->requestor_id]) }}">{{ $p->approver->name }}</a></td>
                      <td>{{ $p->location }}</td>
                      <td>{{ $p->reason }}</td>
                      <td>
                        <a href="{{ route('mco.checkins', ['mid' => $p->id]) }}">
                          <button type="button" class="btn btn-sm btn-info" title="Check-in Info"><i class="fa fa-map-marker"></i></button>
                        </a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
      </div>
    </div>
</div>
@endsection

@section('page-js')

<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>

$(document).ready(function() {
  $('#tapproved').DataTable();
  $('#tpending').DataTable();
});

</script>
@endsection
