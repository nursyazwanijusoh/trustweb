@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-3">
                <div class="card-header">Shared Skillset Management</div>
                <div class="card-body">
                  @if (session()->has('alert'))
                  <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ session()->get('alert') }}</strong>
                  </div>
                  @endif
                  <form method="POST" action="{{ route('ss.add', [], false) }}">
                    @csrf
                    <h5 class="card-title">Add new skill</h5>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Skill Name</label>
                        <div class="col-md-6">
                            <input id="name" class="form-control" type="text" name="name" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="skill_cat" class="col-md-4 col-form-label text-md-right">Skill Category</label>
                        <div class="col-md-6">
                          <select class="form-control" id="skill_cat" name="skill_cat" required>
                            @foreach ($skillcats as $atask)
                            <option value="{{ $atask['id'] }}" >{{ $atask['name'] }}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="skill_type" class="col-md-4 col-form-label text-md-right">Skill Type</label>
                        <div class="col-md-6">
                          <select class="form-control" id="skill_type" name="skill_type" required>
                            @foreach ($skilltypes as $atask)
                            <option value="{{ $atask['id'] }}" >{{ $atask['name'] }}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Add Skill</button>
                        </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="card mb-3">
                @if($cat == 'm')
                <div class="card-header">List of Custom Skillset created by the staff</div>
                @else
                <div class="card-header">List of Predefined Skillset</div>
                @endif

                <div class="card-body">
                  <div class="row mb-0">
                      <div class="col-md-6 offset-md-4">
                          @if($cat == 'p')
                          <button class="btn btn-secondary">Predefined</button>
                          <a href="{{ route('ss.list', ['cat' => 'm'], false) }}"><button class="btn btn-primary">Custom</button></a>
                          @else
                          <a href="{{ route('ss.list', ['cat' => 'p'], false) }}"><button class="btn btn-primary">Predefined</button></a>
                          <button class="btn btn-secondary">Custom</button>
                          @endif
                      </div>
                  </div>

                  <table id="taskdetailtable" class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Category</th>
                        <th scope="col">Type</th>
                        <th scope="col">Name</th>
                        <th scope="col">User Count</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $atask)
                      <tr>
                        <td>{{ $atask->SkillCategory->name }}</td>
                        <td>{{ $atask->SkillType->name }}</td>
                        <td>{{ $atask->name }}</td>
                        <td>{{ $atask->PersonalSkillset->count() }}</td>
                        <td>
                          <button id="btnedit" type="button" class="btn btn-warning btn-sm" title="Edit"
                          data-toggle="modal" data-target="#editCfgModal"
                          data-id="{{$atask['id']}}" data-name="{{$atask['name']}}"
                          data-cat="{{$atask->skill_category_id}}"
                          >Edit</button>
                          <a href="{{ route('ss.del', ['id' => $atask['id']], false) }}">
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
              <h5 class="modal-title" id="exampleModalLabel">Edit Shared Skillset</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('ss.edit', [], false) }}">
              @csrf
              <div class="modal-body">
                <input type="hidden" value="0" name="id" id="edit-id" />
                <div class="form-group row">
                  <label for="edit-name" class="col-sm-4 col-form-label text-sm-right">Name</label>
                  <input type="text" class="form-control col-sm-6" id="edit-name" name="name" required>
                </div>
                <div class="form-group row">
                    <label for="edit-skill_cat" class="col-sm-4 col-form-label text-sm-right">Skill Category</label>
                    <div class="col-sm-6">
                      <select class="form-control" id="edit-skill_cat" name="skill_cat" required>
                        @foreach ($skillcats as $atask)
                        <option value="{{ $atask['id'] }}" >{{ $atask['name'] }}</option>
                        @endforeach
                      </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="edit-skill_cat" class="col-sm-4 col-form-label text-sm-right">Type</label>
                    <div class="col-sm-6">
                      <select class="form-control" id="edit-cat" name="cat" required>
                        @if($cat == 'm')
                        <option value="m" selected >Leave as Custom Skill</option>
                        <option value="p" >Change to Predefined Skill</option>
                        @else
                        <option value="m" >Downgrade to Custom Skill</option>
                        <option value="p" selected>Is a Predefined Skill</option>
                        @endif
                      </select>
                    </div>
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

$('#editCfgModal').on('show.bs.modal', function(e) {

    //get data-id attribute of the clicked element
    var id = $(e.relatedTarget).data('id');
    var name = $(e.relatedTarget).data('name');
    var alat = $(e.relatedTarget).data('cat');

    //populate the textbox
    $(e.currentTarget).find('input[name="id"]').val(id);
    $(e.currentTarget).find('input[name="name"]').val(name);
    document.getElementById("edit-skill_cat").value = alat;
    // $(e.currentTarget).find('input[name="skill_cat"]').find(alat).setAttribute("selected", "");
});

$(document).ready(function() {
    $('#taskdetailtable').DataTable();
} );

</script>
@stop
