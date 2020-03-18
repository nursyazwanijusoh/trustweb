@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-3">
              <div class="card-header">Add Bau Systems / Experiences</div>
              <div class="card-body">
                @if (session()->has('alert'))
                <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>{{ session()->get('alert') }}</strong>
                </div>
                @endif
                <form method="POST" action="{{ route('bauexp.add', [], false) }}">
                  @csrf
                  <div class="form-group row">
                      <label for="descr" class="col-md-4 col-form-label text-md-right">Name</label>
                      <div class="col-md-6">
                          <input id="descr" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" name="name" required autofocus value="{{ old('name') }}">
                          @if ($errors->has('name'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('name') }}</strong>
                              </span>
                          @endif
                      </div>
                      <div class="col-md-2">
                          <button type="submit" class="btn btn-primary">Add</button>
                      </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="card">
                <div class="card-header">List of Experiences</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="taskdetailtable" class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Experience?</th>
                          <th scope="col">Staff Count</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($currtasklist as $atask)
                        <tr>
                          <td>{{ $atask->name }}</td>
                          <td>{{ $atask->Users->count() }}</td>
                          <td>
                            <a href="{{ route('bauexp.staffs', ['id' => $atask->id], false) }}"><button type="button" class="btn btn-info btn-sm" title="Who are they">List</button></a>
                            <button id="btnedit" type="button" class="btn btn-success btn-sm" title="Edit Partner Name"
                            data-toggle="modal" data-target="#editCfgModal"
                            data-id="{{$atask['id']}}" data-name="{{$atask['name']}}"
                            >Edit</button>
                            <a href="{{ route('bauexp.del', ['id' => $atask->id], false) }}"><button type="button" class="btn btn-danger btn-sm" title="delete">Delete</button></a>
                          </td>
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
              <h5 class="modal-title" id="exampleModalLabel">Edit Experience</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('bauexp.edit', [], false) }}">
              @csrf
              <div class="modal-body">
                <input type="hidden" value="0" name="id" id="edit-id" />
                <div class="form-group row">
                  <label for="edit-name" class="col-sm-4 col-form-label text-sm-right">Name</label>
                  <input type="text" class="form-control col-sm-6" id="edit-name" name="name" required>
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
<!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" defer>

$('#editCfgModal').on('show.bs.modal', function(e) {

    //get data-id attribute of the clicked element
    var id = $(e.relatedTarget).data('id');
    var name = $(e.relatedTarget).data('name');

    //populate the textbox
    $(e.currentTarget).find('input[name="id"]').val(id);
    $(e.currentTarget).find('input[name="name"]').val(name);
});

$(document).ready(function() {
    $('#taskdetailtable').DataTable();
} );
</script>
@stop
