@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center no-gutters">
          <div class="col-lg-5">
            <div class="card m-1">
                <div class="card-header">Request MCO Travel Acknowledgement</div>
                <div class="card-body">
                  @if (session()->has('alert'))
                  <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ session()->get('alert') }}</strong>
                  </div>
                  @endif
                  <form method="POST" action="{{ route('mco.submitform') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="actdate" class="col-md-3 col-form-label text-md-right">Date</label>
                        <div class="col-md-6">
                          <input type="date" class="form-control" name="reqdate" value="{{ $mindate }}" min="{{ $mindate }}" max="{{ $maxdate }}" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label id="lbl_title" for="parent_no" class="col-md-3 col-form-label text-md-right">Location</label>
                        <div class="col-md-8" id="inp_title1">
                            <input id="parent_no" class="form-control" type="text" name="location" placeholder="Location of work" required>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="remark" class="col-md-3 col-form-label text-md-right">Reason</label>
                      <div class="col-md-9">
                        <textarea rows="3" class="form-control" id="remark" name="reason" placeholder="Why do you require to travel" required></textarea>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="Approver" class="col-md-3 col-form-label text-md-right">Approver</label>
                      <div class="col-md-9">
                        <input id="Approver" class="form-control" type="text" value="{{ $gm->name }}" readonly />
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-2 text-right">
                        <input class="form-check-input" type="checkbox" name="rate" id="noob" value="1" onchange="checkagree()" required />
                      </div>
                      <div class="col-10">
                        <label class="form-check-label" title="agree to the t&c" for="noob">I hearby agree that I will only travel from home straight to work location, without making any unnecessary stop in between, and then travel straight back home once I'm done with work. <br />Should I fail to do so, I shall accept any kind of punishment imposed by my employer as the consequences of my action.</label>
                      </div>
                    </div>
                    <div class="form-group row mb-0 justify-content-center">
                      <button id="sbtn" type="submit" class="btn btn-primary m-1">Submit Request</button>
                    </div>
                  </form>
                </div>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="card m-1">
              <div class="card-header">Request History</div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="taskdetailtable" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Location</th>
                        <th scope="col">Reason</th>
                        <th scope="col">Status</th>
                        <th scope="col">Document</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($hist as $a)
                      <tr>
                        <td>{{ $a->request_date }}</td>
                        <td>{{ $a->location }}</td>
                        <td>{{ $a->reason }}</td>
                        <td>{{ $a->status }}</td>
                        @if($a->status == 'Approved')
                        <td><a href="{{ route('mco.getpermit', ['mid' => $a->id])}}">
                          <button type="button" class="btn btn-sm btn-info" title="Download travel permit"><i class="fa fa-download"></i></button>
                        </a></td>
                        @else
                        <td></td>
                        @endif
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
  $('#taskdetailtable').DataTable({
      "order": [[ 0, "desc" ]]
    });
});

</script>
@endsection