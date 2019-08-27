@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Add new Avatars</div>
                <div class="card-body">
                  @if(isset($alert))
                  <div class="alert alert-info" role="alert">{{ $alert }}</div>
                  @endif
                  @if($errors->has('rank'))
                  <div class="alert alert-danger" role="alert">
                      <strong>{{ $errors->first('rank') }}</strong>
                  </div>
                  @endif
                  <form method="POST" action="{{ route('avatar.add', [], false) }}">
                    @csrf
                    <!-- <h5 class="card-title">Add new avatar</h5> -->
                    <div class="form-group row">
                        <label for="rank" class="col-md-4 col-form-label text-md-right">Rank</label>
                        <div class="col-md-6">
                            <input id="rank" class="form-control{{ $errors->has('rank') ? ' is-invalid' : '' }}" type="number" step="1" name="rank" value="{{ old('rank') }}" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="rank_name" class="col-md-4 col-form-label text-md-right">Rank name</label>
                        <div class="col-md-6">
                          <input id="rank_name" class="form-control" value="{{ old('rank_name') }}" type="text" name="rank_name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="min_hours" class="col-md-4 col-form-label text-md-right">Minimum Hour</label>
                        <div class="col-md-6">
                          <input id="min_hours" class="form-control" value="{{ old('min_hours') }}" type="number" name="min_hours" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="max_hours" class="col-md-4 col-form-label text-md-right">Maximum Hour</label>
                        <div class="col-md-6">
                          <input id="max_hours" class="form-control" value="{{ old('max_hours') }}" type="number" name="max_hours" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="image_url" class="col-md-4 col-form-label text-md-right">Image URL</label>
                        <div class="col-md-6">
                          <input id="image_url" class="form-control" value="{{ old('image_url') }}" type="text" name="image_url" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="image_credit" class="col-md-4 col-form-label text-md-right">Image Credit</label>
                        <div class="col-md-6">
                          <input id="image_credit" class="form-control" value="{{ old('image_credit') }}" type="text" name="image_credit" required>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Add Config</button>
                        </div>
                    </div>
                  </form>
                </div>
              </div><br />
              <div class="card">
                <div class="card-header">List of Avatars</div>
                <div class="card-body">
                  <table id="fblist" class="table table-striped table-hover table-responsive">
                    <thead>
                      <tr>
                        <th scope="col">Rank</th>
                        <th scope="col">Rank Name</th>
                        <th scope="col">Min Hour</th>
                        <th scope="col">Max Hour</th>
                        <th scope="col">Image URL</th>
                        <th scope="col">Image Credit</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $atask)
                      <tr>
                        <td>{{ $atask['rank'] }}</td>
                        <td>{{ $atask['rank_name'] }}</td>
                        <td>{{ $atask['min_hours'] }}</td>
                        <td>{{ $atask['max_hours'] }}</td>
                        <td>{{ $atask['image_url'] }}</td>
                        <td>{{ $atask['image_credit'] }}</td>
                        <td>
                          <button id="btnedit" type="button" class="btn btn-warning btn-sm" title="Edit"
                            data-toggle="modal" data-target="#editCfgModal"
                            data-id="{{ $atask->id }}"
                            data-rank="{{ $atask->rank }}"
                            data-rank_name="{{ $atask->rank_name }}"
                            data-min_hours="{{ $atask->min_hours }}"
                            data-max_hours="{{ $atask->max_hours }}"
                            data-image_url="{{ $atask->image_url }}"
                            data-image_credit="{{ $atask->image_credit }}"
                            >Edit
                          </button>
                          <a href="{{ route('avatar.del', ['id' => $atask['id']], false) }}">
                            <button type="button" class="btn btn-danger btn-sm" title="Delete">Delete</button>
                          </a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
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
