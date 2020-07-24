@extends('layouts.app')

@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection


@section('content')
<div class="container-fluid">
  @if (session()->has('alert'))
  <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>{{ session()->get('alert') }}</strong>
  </div>
  @endif
    <div class="row justify-content-center">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">Manual Zerorize - {{ $staff->name }}</div>
                <div class="card-body">
                  <form method="POST" action="{{ route('mleave.add', [], false) }}">
                    @csrf
                    <input type="hidden" name="staff_id" value="{{ $staff->id }}" >
                    <div class="form-group row">
                        <label for="fdate" class="col-md-4 col-form-label text-md-right">From Date</label>
                        <div class="col-md-8">
                          <input type="date" class="form-control" name="fdate" id="fdate" required/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tdate" class="col-md-4 col-form-label text-md-right">To Date</label>
                        <div class="col-md-8">
                          <input type="date" class="form-control" name="tdate" id="tdate" required/>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="livtaip" class="col-md-4 col-form-label text-md-right">Leave Type</label>
                      <div class="col-md-8">
                        <select class="form-control" id="livtaip" name="livtaip" required>
                          @foreach ($ltype as $act)
                          <option value="{{ $act->id }}" >{{ $act->code }} - {{ $act->descr }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                        <label for="remark" class="col-md-4 col-form-label text-md-right">Remark</label>
                        <div class="col-md-8">
                            <input id="remark" class="form-control" type="text" name="remark" required />
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                      <button type="submit" class="btn btn-danger">Zerorize Hours. NO UNDO!</button>
                    </div>
                  </form>
                  <br />
                </div>
              </div>
            </div>
            <div class="col-md-8 mb-3">
              <div class="card">
                <div class="card-header">List of Manual Leave</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="mcuti" class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th scope="col">From</th>
                          <th scope="col">To</th>
                          <th scope="col">Leave Type</th>
                          <th scope="col">Remark</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($mcuti as $acts)
                        <tr>
                          <td>{{ $acts->start_date }}</td>
                          <td>{{ $acts->end_date }}</td>
                          <td>{{ $acts->LeaveType->descr }}</td>
                          <td>{{ $acts->remark }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="card">
              <div class="card-header">List of Loaded leave from SAP</div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="lcuti" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">From</th>
                        <th scope="col">To</th>
                        <th scope="col">Leave Type</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($lcuti as $acts)
                      <tr>
                        <td>{{ $acts->start_date }}</td>
                        <td>{{ $acts->end_date }}</td>
                        <td>{{ $acts->LeaveType->descr }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
          <div class="card">
            <div class="card-header">List of Leave record from SAP</div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="scuti" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th scope="col">From</th>
                      <th scope="col">To</th>
                      <th scope="col">Leave Type</th>
                      <th scope="col">Status</th>
                      <th scope="col">Operation</th>
                      <th scope="col">Load Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($scuti as $acts)
                    <tr>
                      <td>{{ $acts->date_start }}</td>
                      <td>{{ $acts->date_end }}</td>
                      <td>{{ $acts->leave_describtion }}</td>
                      <td>{{ $acts->status }}</td>
                      <td>{{ $acts->operation }}</td>
                      <td>{{ $acts->load_status }}</td>
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
<!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" defer>
$(document).ready(function() {
    $('#mcuti').DataTable();
    $('#lcuti').DataTable();
    $('#scuti').DataTable();
    $('#livtaip').select2();
} );
</script>
@stop
