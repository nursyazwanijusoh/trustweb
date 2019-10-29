@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">Add new Public Holiday</div>
                <div class="card-body">
                  @if(session()->has('alert'))
                  <div class="alert alert-info" role="alert">{{ session()->get('alert') }}</div>
                  @endif
                  <form method="POST" action="{{ route('ph.add', [], false) }}">
                    @csrf
                    <!-- <h5 class="card-title">Add new avatar</h5> -->
                    <div class="form-group row">
                        <label for="event_date" class="col-md-4 col-form-label text-md-right">Date</label>
                        <div class="col-md-6">
                          <input id="event_date" class="form-control" value="{{ old('event_date') }}" type="date" name="event_date" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Event Name</label>
                        <div class="col-md-6">
                            <input id="name" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" value="{{ old('name') }}" required>
                        </div>
                    </div>


                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Add Public Holiday</button>
                        </div>
                    </div>
                  </form>
                </div>
              </div><br />
              <div class="card">
                <div class="card-header">List of Public Holidays</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="fblist" class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Date</th>
                          <th scope="col">Event Name</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($data as $atask)
                        <tr>
                          <td>{{ $atask->event_date }}</td>
                          <td>{{ $atask->name }}</td>
                          <td>
                            <button id="btnedit" type="button" class="btn btn-warning btn-sm" title="Edit"
                              data-toggle="modal" data-target="#editCfgModal"
                              data-id="{{ $atask->id }}"
                              data-event_date="{{ $atask->event_date }}"
                              data-name="{{ $atask->name }}"
                              >Edit
                            </button>
                            <!-- <a href="{{ route('ph.del', ['id' => $atask['id']], false) }}">
                              <button type="button" class="btn btn-danger btn-sm" title="Delete">Delete</button>
                            </a> -->
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
              <h5 class="modal-title" id="exampleModalLabel">Edit Config</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('avatar.add', [], false) }}">
              @csrf
              <div class="modal-body">
                <input type="hidden" value="0" name="id" id="edit-id" />
                <div class="form-group row">
                  <label for="edit-rank" class="col-sm-4 col-form-label text-sm-right">Rank:</label>
                  <input type="number" step="1" class="form-control col-sm-6" id="edit-rank" name="rank" value="test" required>
                </div>
                <div class="form-group row">
                  <label for="edit-rank_name" class="col-sm-4 col-form-label text-sm-right">Rank name:</label>
                  <input type="text" class="form-control col-sm-6" id="edit-rank_name" name="rank_name" required>
                </div>
                <div class="form-group row">
                  <label for="edit-min_hours" class="col-sm-4 col-form-label text-sm-right">Min Hours:</label>
                  <input type="number" class="form-control col-sm-6" id="edit-min_hours" name="min_hours" value="test" required>
                </div>
                <div class="form-group row">
                  <label for="edit-max_hours" class="col-sm-4 col-form-label text-sm-right">Max Hours:</label>
                  <input type="text" class="form-control col-sm-6" id="edit-max_hours" name="max_hours" required>
                </div>
                <div class="form-group row">
                  <label for="edit-image_url" class="col-sm-4 col-form-label text-sm-right">Image URL:</label>
                  <input type="text" class="form-control col-sm-6" id="edit-image_url" name="image_url" value="test" required>
                </div>
                <div class="form-group row">
                  <label for="edit-image_credit" class="col-sm-4 col-form-label text-sm-right">Image Credit:</label>
                  <input type="text" class="form-control col-sm-6" id="edit-image_credit" name="image_credit" required>
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
    var rank = $(e.relatedTarget).data('rank');
    var rank_name = $(e.relatedTarget).data('rank_name');
    var min_hours = $(e.relatedTarget).data('min_hours');
    var max_hours = $(e.relatedTarget).data('max_hours');
    var image_url = $(e.relatedTarget).data('image_url');
    var image_credit = $(e.relatedTarget).data('image_credit');

    //populate the textbox
    $(e.currentTarget).find('input[name="id"]').val(id);
    $(e.currentTarget).find('input[name="rank"]').val(rank);
    $(e.currentTarget).find('input[name="rank_name"]').val(rank_name);
    $(e.currentTarget).find('input[name="min_hours"]').val(min_hours);
    $(e.currentTarget).find('input[name="max_hours"]').val(max_hours);
    $(e.currentTarget).find('input[name="image_url"]').val(image_url);
    $(e.currentTarget).find('input[name="image_credit"]').val(image_credit);
    $(e.currentTarget).find('input[name="rank"]').focus();
});
</script>
@stop
