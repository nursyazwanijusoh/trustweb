@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center no-gutters">
          <div class="col-xl-12">
            <div class="card mb-3">
              <div class="card-header">MCO Travel - Pending Approval</div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="tpending" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Name</th>
                        <th scope="col">Unit</th>
                        <th scope="col">Location</th>
                        <th scope="col">Reason</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($pending as $p)
                      <tr>
                        <td>{{ $p->request_date }}</td>
                        <td><a href="{{ route('staff', ['staff_id' => $p->requestor_id]) }}">{{ $p->requestor->name }}</a></td>
                        <td>{{ $p->requestor->subunit }}</td>
                        <td>{{ $p->location }}</td>
                        <td>{{ $p->reason }}</td>
                        <td>
                          <form method="post" action="{{ route('mco.takeaction')}}">
                            @csrf
                            <input type="hidden" name="mid" value="{{ $p->id }}" />
                            <button type="submit" class="btn btn-sm btn-link text-success" name="action" value="approve" title="Approve"><i class="fa fa-check"></i></button>
                            <button type="submit" class="btn btn-sm btn-link text-danger" name="action" value="reject" title="Reject"><i class="fa fa-times"></i></button>
                          </form>

                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
        <div class="col-xl-12">
          <div class="card mb-3">
            <div class="card-header">Approved Request</div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="tapproved" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th scope="col">Date</th>
                      <th scope="col">Name</th>
                      <th scope="col">Unit</th>
                      <th scope="col">Location</th>
                      <th scope="col">Reason</th>
                      <th scope="col">Details</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($approved as $p)
                    <tr>
                      <td>{{ $p->request_date }}</td>
                      <td><a href="{{ route('staff', ['staff_id' => $p->requestor_id]) }}">{{ $p->requestor->name }}</a></td>
                      <td>{{ $p->requestor->subunit }}</td>
                      <td>{{ $p->location }}</td>
                      <td>{{ $p->reason }}</td>
                      <td>
                        <a href="{{ route('mco.checkins', ['mid' => $p->id]) }}">
                          <button type="button" class="btn btn-sm btn-info" title="Check-in Info"><i class="fa fa-map-marker"></i></button>
                        </a>
                        <a href="{{ route('mco.getpermit', ['mid' => $p->id]) }}">
                          <button type="button" class="btn btn-sm btn-success" title="Download travel permit"><i class="fa fa-file"></i></button>
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
