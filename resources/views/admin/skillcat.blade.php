@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Skill Category Management</div>
                <div class="card-body">
                  @if(isset($alert))
                  <div class="alert alert-info" role="alert">{{ $alert }}</div>
                  @endif
                  <form method="POST" action="{{ route('sc.add', [], false) }}">
                    @csrf
                    <h5 class="card-title">Add new skill category?</h5>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                        <div class="col-md-6">
                            <input id="name" class="form-control" type="text" name="name" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="seqorder" class="col-md-4 col-form-label text-md-right">Sequence Order</label>
                        <div class="col-md-6">
                          <input id="seqorder" class="form-control" type="number" name="seqorder" required>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Add Skill Category</button>
                        </div>
                    </div>
                  </form>
                </div>
                <div class="card-header"> </div>
                <div class="card-body">
                  <h5 class="card-title">List of Skill Category</h5>
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Sequence</th>
                        <th scope="col">Name</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $atask)
                      <tr>
                        <td>{{ $atask['sequence'] }}</td>
                        <td>{{ $atask['name'] }}</td>
                        <td>
                          <button id="btnedit" type="button" class="btn btn-warning btn-sm" title="Edit"
                          data-toggle="modal" data-target="#editCfgModal"
                          data-id="{{$atask['id']}}" data-name="{{$atask['name']}}"
                          data-seq="{{$atask['sequence']}}"
                          >Edit</button>
                          <a href="{{ route('sc.del', ['id' => $atask['id']], false) }}">
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
              <h5 class="modal-title" id="exampleModalLabel">Edit Skill Category</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('sc.edit', [], false) }}">
              @csrf
              <div class="modal-body">
                <input type="hidden" value="0" name="id" id="edit-id" />
                <div class="form-group row">
                  <label for="edit-name" class="col-sm-4 col-form-label text-sm-right">Name</label>
                  <input type="text" class="form-control col-sm-6" id="edit-name" name="name" required>
                </div>
                <div class="form-group row">
                  <label for="edit-seq" class="col-sm-4 col-form-label text-sm-right">Sequence</label>
                  <input type="number" class="form-control col-sm-6" id="edit-seq" name="seqorder" required>
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
    var name = $(e.relatedTarget).data('name');
    var alat = $(e.relatedTarget).data('seq');

    //populate the textbox
    $(e.currentTarget).find('input[name="id"]').val(id);
    $(e.currentTarget).find('input[name="name"]').val(name);
    $(e.currentTarget).find('input[name="seqorder"]').val(alat);
});
</script>
@stop
