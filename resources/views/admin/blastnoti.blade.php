@extends('layouts.app')

@section('page-css')
<style>
.loader {
  border: 16px solid #f3f3f3; /* Light grey */
  border-top: 16px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 120px;
  height: 120px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Blast Push Notification</div>
                <div class="card-body">
                  <form method="POST" action="{{ route('pn.reg', [], false) }}">
                    @csrf
                    <div class="form-group row">
                        <label for="descr" class="col-md-4 col-form-label text-md-right">Title</label>
                        <div class="col-md-6">
                            <input id="descr" class="form-control" type="text" maxlength="200" name="title" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="remark" class="col-md-4 col-form-label text-md-right">Notification Content</label>
                        <div class="col-md-6">
                          <textarea rows="3" maxlength="1900" class="form-control" id="remark" name="body"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Target User</label>
                        <div class="col-md-6">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="isglobal" onclick="rbselected()" id="isglubal" value="true" checked>
                            <label class="col-form-label" for="isglubal">Global</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="isglobal" onclick="rbselected()" id="isnotglubal" value="false">
                            <label class="col-form-label" for="isnotglubal">Specific Group</label>
                          </div>
                        </div>
                    </div>
                    <div id="grpselector" class="form-group row d-none">
                        @foreach($group as $abuild)
                        <div class="col-md-4">
                          <label class="checkbox-inline">
                            <input type="checkbox" value="{{ $abuild['id'] }}" name="grplist[]" >
                            {{ $abuild->name }}
                          </label>
                        </div>
                        @endforeach
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col text-center">
                            <button type="submit" class="btn btn-primary">Send Push Notification</button>
                        </div>
                    </div>
                  </form>
                </div>
            </div>
            @if(session()->has('pn_id'))
            <br />
            <div class="card">
                <div class="card-header">Notification Blast Result</div>
                <div class="card-body text-center">
                  <div id="nrest">
                     <div class="loader">In progress..</div>
                  </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('page-js')

@if(session()->has('pn_id'))
<script type="text/javascript">

$(document).ready(function() {

  // alert('{{ route("pn.dosend", ["pn_id" => session()->get("pn_id")]) }}');
  const url='{{ route("pn.dosend", ["pn_id" => session()->get("pn_id")]) }}';

  $.ajax({
    url: url ,
    type: "GET",
    success: function(resp) {
      updateResp(resp);
    },
    error: function(err) {
      updateResp(err);
    }
  });


} );

function updateResp(respjson){
  // var out = '<code>' + JSON.stringify(respjson) + '</code>'
  var out = '<pre>Status: ' + respjson.status
    + '<br />Message: ' + respjson.msg
    + '<br />Recipient count: ' + respjson.rcount
    + '</pre>';
  document.getElementById("nrest").innerHTML  = out;
}



</script>
@endif

<script type="text/javascript">
function rbselected(){
  if(document.getElementById("isglubal").checked == true){
    document.getElementById("grpselector").className  = "form-group row d-none";
  } else {
    document.getElementById("grpselector").className  = "form-group row";
  }
}
</script>

@endsection
