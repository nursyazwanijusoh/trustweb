@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Common Configs Management</div>
                <div class="card-body">
                  @if(isset($alert))
                  <div class="alert alert-info" role="alert">{{ $alert }}</div>
                  @endif
                  <form method="POST" action="{{ route('cfg.add', [], false) }}">
                    @csrf
                    <h5 class="card-title">Add new config?</h5>
                    <div class="form-group row">
                        <label for="key" class="col-md-4 col-form-label text-md-right">Key</label>
                        <div class="col-md-6">
                            <input id="key" class="form-control" type="text" name="key" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="value" class="col-md-4 col-form-label text-md-right">Value</label>
                        <div class="col-md-6">
                          <input id="value" class="form-control" type="text" name="value" required>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Add Config</button>
                        </div>
                    </div>
                  </form>
                </div>
                <div class="card-header"> </div>
                <div class="card-body">
                  <h5 class="card-title">List of Configs</h5>
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Key</th>
                        <th scope="col">Value</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $atask)
                      <tr>
                        <td>{{ $atask['key'] }}</td>
                        <td>{{ $atask['value'] }}</td>
                        <td>
                          <button id="btnedit" type="button" class="btn btn-warning btn-sm" title="Edit"
                          data-toggle="modal" data-target="#editCfgModal"
                          data-id="{{$atask['id']}}" data-key="{{$atask['key']}}" data-value="{{$atask['value']}}">Edit</button>
                          <a href="{{ route('cfg.del', ['id' => $atask['id']], false) }}">
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
            <form method="POST" action="{{ route('cfg.edit', [], false) }}">
              @csrf
              <div class="modal-body">
                <input type="hidden" value="0" name="id" id="edit-id" />
                <div class="form-group row">
                  <label for="edit-key" class="col-sm-4 col-form-label text-sm-right">Key:</label>
                  <input type="text" class="form-control col-sm-6" id="edit-key" name="key" value="test">
                </div>
                <div class="form-group row">
                  <label for="edit-value" class="col-sm-4 col-form-label text-sm-right">Value:</label>
                  <input type="text" class="form-control col-sm-6" id="edit-value" name="value" autofocus>
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
<script type="text/javascript">

$('#editCfgModal').on('show.bs.modal', function(e) {

    //get data-id attribute of the clicked element
    var id = $(e.relatedTarget).data('id');
    var key = $(e.relatedTarget).data('key');
    var value = $(e.relatedTarget).data('value');

    //populate the textbox
    $(e.currentTarget).find('input[name="id"]').val(id);
    $(e.currentTarget).find('input[name="key"]').val(key);
    $(e.currentTarget).find('input[name="value"]').val(value);
    $(e.currentTarget).find('input[name="value"]').focus();
});
</script>
@stop
