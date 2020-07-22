@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
              <div class="card">
                <div class="card-header">Create Project</div>
                <div class="card-body">
                  @if (session()->has('alert'))
                  <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ session()->get('alert') }}</strong>
                  </div>
                  @endif
                  <form method="POST" action="{{ route('opp.project.add', [], false) }}">
                    @csrf
                    <div class="form-group row">
                        <label for="project_no" class="col-md-3 col-form-label text-md-right">Project ID</label>
                        <div class="col-md-3" >
                          <input id="project_no" class="form-control{{ $errors->has('project_no') ? ' is-invalid' : '' }}" value="{{ old('project_no') }}" type="text" name="project_no" required autofocus>
                          @if ($errors->has('project_no'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('project_no') }}</strong>
                              </span>
                          @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="pm_no" class="col-md-3 col-form-label text-md-right">Project Manager</label>
                        <div class="col-md-3" >
                          <input id="pm_no" class="form-control{{ $errors->has('pm_no') ? ' is-invalid' : '' }}" value="{{ old('pm_no') }}" type="text" name="pm_no" placeholder="staff no only" required onblur="getPMName()" />
                          @if ($errors->has('pm_no'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('pm_no') }}</strong>
                              </span>
                          @endif
                        </div>
                        <label class="col-md-6 col-form-label text-secondary" id="pm_namae"></label>
                    </div>
                    <div class="form-group row">
                        <label for="title" class="col-md-3 col-form-label text-md-right">Project Name</label>
                        <div class="col-md-8" >
                            <input id="title" class="form-control" type="text" name="title" value="{{old('title')}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="remark" class="col-md-3 col-form-label text-md-right">Extra Info</label>
                        <div class="col-md-8">
                          <textarea rows="3" class="form-control" id="remark" name="desc" placeholder="Additional information about this poll" required>{{ old('desc') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="close_date" class="col-md-3 col-form-label text-md-right">End Date</label>
                        <div class="col-md-3" >
                            <input type="date" class="form-control" name="close_date" id="close_date" value="{{ old('close_date', $tomorrow) }}" min="{{ $tomorrow }}"/>
                        </div>
                    </div>
                    <div class="form-group row mb-0 justify-content-center">
                        <button type="submit" class="btn btn-primary m-1">Create Project</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script type="text/javascript" defer>
$(document).ready(function() {

} );

function getPMName(){
  var inp = document.getElementById('pm_no').value;

  if(inp.length > 5){
    document.getElementById('pm_namae').innerHTML = 'Fetching staff name';

    var search_url = "{{ route('webapi.findstaff') }}";

    $.ajax({
      url: search_url,
      data: {
        'input' : inp
      },
      success: function(result) {
        if(result.length == 0){
          document.getElementById('pm_namae').innerHTML = 'Staff not found';
        } else {
          if(result.length == 1){
            document.getElementById('pm_namae').innerHTML = result[0].name;
          } else {
            document.getElementById('pm_namae').innerHTML = 'Too many result. Please double check the staff no';
          }
        }

      },
      error: function(xhr){
        document.getElementById('pm_namae').innerHTML = xhr.statusText;
        // alert("An error occured: " + xhr.status + " " + xhr.statusText);
      }
    });
  }

}

</script>
@stop
