@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card mb-3">
        <div class="card-header">Manual Load Data</div>
        <div class="card-body">
          <button id="btnOM" type="button" class="btn btn-xl btn-info m-3" onclick="loadOM()">OM Data: {{ $eplist }}</button>
          <button id="btnCuti" type="button" class="btn btn-xl btn-info m-3" onclick="loadCuti()">Leave Data: {{ $cuticount }}</button>
          <button id="btnSkill" type="button" class="btn btn-xl btn-info m-3" onclick="loadSkill()">Skillset Data: {{ $skillcount }}</button>
          <div id="pbar" class="progress m-3" style="display: none">
            <div id="pbari" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%;">load persno</div>
          </div>
        </div>
      </div>
      <div class="card mb-3">
        <div class="card-header">Load Team Indicator Data</div>
        <div class="card-body">
          @if(isset($dataloaded))
          <p class="card-text">
            <b>Upload result:</b> <br />
            Total record: {{ $totcount }}<br />
            Total success: {{ $totsuccess }}<br />
            Total failure: {{ $toterr }}<br />
          </p>
          @endif
          <p class="card-text">
            <b>input text file format:</b> <br />
            staff_no;team_label
          </p>
          <form method="post" action="{{route('admin.uploadTeamAB')}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
                <label for="infile" class="col-md-3 col-form-label text-md-right">Select input file </label>
                <div class="col-md-5">
                  <input type="file" class="form-control{{ $errors->has('infile') ? ' is-invalid' : '' }}" name="infile" id="infile"  required/>
                  @if ($errors->has('infile'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('infile') }}</strong>
                      </span>
                  @endif
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-primary">Upload</button>
                </div>
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

  function loadCuti(){
    document.getElementById("btnCuti").disabled = true;
    document.getElementById("pbar").style = "display: block";
    document.getElementById("pbari").innerHTML = "Loading data cuti";

    var url = '{{ route("admin.loadDataCuti", [], false) }}';
    var aftercount = 0;

    $.ajax({
      url: url ,
      type: "GET",
      success: function(resp) {
        location.reload();
      },
      error: function(err) {
        alert(err.responseText);
        location.reload();
      }
    });

  }

  function loadOM(){
    document.getElementById("btnOM").disabled = true;
    document.getElementById("pbar").style = "display: block";
    document.getElementById("pbari").innerHTML = "Updating OM info";

    var url = '{{ route("admin.processOM", [], false) }}';
    var aftercount = 0;

    $.ajax({
      url: url ,
      type: "GET",
      success: function(resp) {
        location.reload();
      },
      error: function(err) {
        alert(err.responseText);
        location.reload();
      }
    });
  }

  function loadSkill(){
    document.getElementById("btnSkill").disabled = true;
    document.getElementById("pbar").style = "display: block";
    document.getElementById("pbari").innerHTML = "Loading skillset data";

    var url = '{{ route("admin.loadDataSkill", [], false) }}';
    var aftercount = 0;

    $.ajax({
      url: url ,
      type: "GET",
      success: function(resp) {
        location.reload();
      },
      error: function(err) {
        alert(err.responseText);
        location.reload();
      }
    });

    //
    // document.getElementById("btnPersno").innerHTML = "Null SAP Persno: " + aftercount;
    // document.getElementById("btnPersno").disabled = false;
    // document.getElementById("pbar").style = "display: none";
  }

</script>
@endsection
