@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-3">
                <div class="card-header">Add new skill type</div>
                <div class="card-body">
                  @if (session()->has('alert'))
                  <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ session()->get('alert') }}</strong>
                  </div>
                  @endif
                  <form method="POST" action="{{ route('st.add', [], false) }}">
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                        <div class="col-md-6">
                            <input id="name" class="form-control" type="text" name="name" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="skill_cat" class="col-md-4 col-form-label text-md-right">Skill Category</label>
                        <div class="col-md-6">
                          <select class="form-control" id="skill_cat" name="skill_cat" required>
                            @foreach ($catlist as $atask)
                            <option value="{{ $atask['id'] }}" >{{ $atask['name'] }}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Add Skill Type</button>
                        </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="card mb-3">
                <div class="card-header">List of Skill Type</div>
                <div class="card-body">
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Category</th>
                        <th scope="col">Name</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $atask)
                      <tr>
                        <td>{{ $atask->Category->name }}</td>
                        <td>{{ $atask->name }}</td>
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
