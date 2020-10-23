@extends('layouts.app')

@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
.note-group-select-from-files {
  display: none;
}
</style>
@endsection


@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">Add News</div>
                <div class="card-body">
                  @if (session()->has('alert'))
                  <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ session()->get('alert') }}</strong>
                  </div>
                  @endif
                  <form method="POST" action="{{ route('admin.news.add', [], false) }}">
                    @csrf
                    <div class="form-group row">
                        <label for="content" class="col-md-2 col-form-label text-md-right">Title</label>
                        <div class="col-md-6">
                            <input id="content" class="form-control" type="text" name="title" required autofocus />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="remark" class="col-md-2 col-form-label text-md-right">Content</label>
                        <div class="col-md-8">
                          <textarea rows="6" class="form-control" id="remark" name="content" placeholder="Get creative" required>{{ old('details') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                      <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Add</button>
                        <button type="button" class="btn btn-success" title="Preview" data-toggle="modal" data-target="#editCfgModal"
                        data-id="preview">Preview</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
          </div>
          <div class="col-md-12">
              <div class="card">
                <div class="card-header">News List</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="fblist" class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Date Added</th>
                          <th scope="col">Title</th>
                          <th scope="col">Added By</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($news as $atask)
                        <tr>
                          <td>{{ $atask->created_at }}</td>
                          <td>{{ $atask->title }}</td>
                          <td>{{ $atask->Creator->name }}</td>
                          <td>
                            <form method="post" action="{{ route('admin.news.del', [], false) }}">
                              @csrf
                              <input type="hidden" name="id" value="{{ $atask->id }}" />
                              <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Confirm delete?')"><i class="fa fa-trash"></i></button>
                              <button type="button" class="btn btn-sm btn-success" title="Preview" data-toggle="modal" data-target="#editCfgModal"
                              data-id="{{ $atask->id }}"><i class="fa fa-eye"></i></button>
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

        <div class="modal fade" id="editCfgModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">News Preview</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="modalbody">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" defer>

$('#editCfgModal').on('show.bs.modal', function(e) {

    //get data-id attribute of the clicked element
    var id = $(e.relatedTarget).data('id');

    if(id == "preview"){
      document.getElementById("modalbody").innerHTML = document.getElementById("remark").value;
    } else {
      var baseeurl='{{ route("admin.news.api.detail") }}';

      $.ajax({
        url: baseeurl + "?nid=" + id ,
        type: "GET",
        success: function(resp) {
          // alert(resp);
          document.getElementById("modalbody").innerHTML = resp;
        },
        error: function(err) {
          $('#editCfgModal').modal('hide');
          alert(err);
        }
      });
    }

});

$(document).ready(function() {
    $('#remark').summernote();
    $('#fblist').DataTable({
        "order": [[ 0, "desc" ]]
      });
} );
</script>
@stop
