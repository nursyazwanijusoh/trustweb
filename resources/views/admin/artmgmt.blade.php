@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-3">
                <div class="card-header">Agile Resource Team Member</div>
                <div class="card-body">
                  @if (session()->has('alert'))
                  <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ session()->get('alert') }}</strong>
                  </div>
                  @endif
                  <form method="POST" action="{{ route('art.add', [], false) }}">
                    @csrf
                    <h5 class="card-title">Assign staff as ART member</h5>
                    <div class="form-group row">
                        <label for="repno" class="col-md-4 col-form-label text-md-right">Staff No</label>
                        <div class="col-md-3">
                          <input id="repno" class="form-control{{ $errors->has('repno') ? ' is-invalid' : '' }}" value="{{ old('repno') }}" type="text" name="repno" maxlength="15" required>
                          @if ($errors->has('repno'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('repno') }}</strong>
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
                <div class="card-header">Agile Resource Team Members</div>
                <div class="card-body">
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Staff No</th>
                        <th scope="col">Name</th>
                        <th scope="col">Position</th>
                        <th scope="col">Unit</th>
                        <th scope="col">Remove</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $atask)
                      <tr>
                        <td>{{ $atask->User->staff_no }}</td>
                        <td>{{ $atask->User->name }}</td>
                        <td>{{ $atask->User->position }}</td>
                        <td>{{ $atask->User->subunit }}</td>
                        <td>
                          <form method="post" action="{{ route('art.del') }}">
                            <input type="hidden" name="artid" value="{{ $atask->id }}" />
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">Delete</button>
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
