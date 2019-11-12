@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">Add new Leave Type</div>
                <div class="card-body">
                  @if(session()->has('alert'))
                  <div class="alert alert-info" role="alert">{{ session()->get('alert') }}</div>
                  @endif
                  <form method="POST" action="{{ route('leave.add', [], false) }}">
                    @csrf
                    <!-- <h5 class="card-title">Add new avatar</h5> -->
                    <div class="form-group row">
                        <label for="incode" class="col-md-4 col-form-label text-md-right">Leave Code (SAP)</label>
                        <div class="col-md-6">
                          <input id="incode" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" value="{{ old('code') }}" type="text" name="code" maxlength="10" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="descr" class="col-md-4 col-form-label text-md-right">Description</label>
                        <div class="col-md-6">
                            <input id="descr" name="descr" class="form-control{{ $errors->has('descr') ? ' is-invalid' : '' }}" type="text" value="{{ old('descr') }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="hours_value" class="col-md-4 col-form-label text-md-right">Expected work hours</label>
                        <div class="col-md-6">
                            <input id="hours_value" name="hours_value" class="form-control{{ $errors->has('hours_value') ? ' is-invalid' : '' }}" type="number" value="{{ old('hours_value') }}" min="0" max="8" step="0.1" required>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Add Leave Type</button>
                        </div>
                    </div>
                  </form>
                </div>
              </div><br />
              <div class="card">
                <div class="card-header">List of Leave Type</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="fblist" class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th scope="col">SAP Code</th>
                          <th scope="col">Description</th>
                          <th scope="col">Expected Work Hours</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($data as $atask)
                        <tr>
                          <td>{{ $atask->code }}</td>
                          <td>{{ $atask->descr }}</td>
                          <td>{{ $atask->hours_value }}</td>
                          <td>
                            <button id="btnedit" type="button" class="btn btn-warning btn-sm" title="Edit"
                              data-toggle="modal" data-target="#editCfgModal"
                              data-id="{{ $atask->id }}"
                              data-code="{{ $atask->code }}"
                              data-descr="{{ $atask->descr }}"
                              data-hours="{{ $atask->hours_value }}"
                              >Edit
                            </button>
                            <a href="{{ route('leave.del', ['id' => $atask->id], false) }}">
                              <button type="button" class="btn btn-danger btn-sm" title="Delete">Delete</button>
                            </a>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
            </div><br />
        </div>
        <div class="modal fade" id="editCfgModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit Leave Type</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('leave.edit', [], false) }}">
              @csrf
              <div class="modal-body">
                <input type="hidden" value="0" name="id" id="edit-id" />
                <div class="form-group row">
                  <label for="edit-edate" class="col-sm-4 col-form-label text-sm-right">SAP Code:</label>
                  <input type="text" maxlength="10" class="form-control col-sm-6" id="edit-code" name="code" value="test" readonly>
                </div>
                <div class="form-group row">
                  <label for="edit-descr" class="col-sm-4 col-form-label text-sm-right">Description</label>
                  <input type="text" class="form-control col-sm-6" id="edit-descr" name="descr" required>
                </div>
                <div class="form-group row">
                  <label for="edit-hours" class="col-sm-4 col-form-label text-sm-right">Work Hours</label>
                  <input type="number" min="0" max="8" step="0.1" class="form-control col-sm-6" id="edit-descr" name="hours_value" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@section('page-js')
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">

$(document).ready(function() {
    $('#fblist').DataTable();
} );

$('#editCfgModal').on('show.bs.modal', function(e) {

    //get data-id attribute of the clicked element
    var id = $(e.relatedTarget).data('id');
    var rank = $(e.relatedTarget).data('code');
    var rank_name = $(e.relatedTarget).data('descr');
    var hours = $(e.relatedTarget).data('hours');

    //populate the textbox
    $(e.currentTarget).find('input[name="id"]').val(id);
    $(e.currentTarget).find('input[name="code"]').val(rank);
    $(e.currentTarget).find('input[name="descr"]').val(rank_name);
    $(e.currentTarget).find('input[name="hours_value"]').val(hours);
    $(e.currentTarget).find('input[name="name"]').focus();
});
</script>

@stop
