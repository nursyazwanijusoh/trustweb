@extends('layouts.app')

@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

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
                        <select class="form-control" id="Approver" name="gmid" required>
                          @foreach ($gm as $pbe)
                          <optgroup label="{{ $pbe['pos'] }}">
                            <option value="{{ $pbe['id'] }}">{{ $pbe['name'] }}</option>
                          </optgroup>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-2 text-right">
                        <input class="form-check-input" type="checkbox" name="rate" id="noob" value="1" required />
                      </div>
                      <div class="col-10">
                        <label class="form-check-label" title="agree to the t&c" for="noob">I hereby declare that I shall travel from home to workplace and vice versa without making any unnecessary stops in between upon completion of my assigned work. <br />Failure to comply, I understand that my employer may invoke an appropriate disciplinary action as the consequence of my action.</label>
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
              <div class="card-header">Request History for <a href="{{ route('staff', ['staff_id' => $tuser->id])}}">{{ $tuser->name }}</a></div>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script>

$(document).ready(function() {
  $('#Approver').select2();

  $('#taskdetailtable').DataTable({
      "order": [[ 0, "desc" ]]
    });
});

</script>
@endsection
