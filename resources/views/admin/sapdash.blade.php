@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card mb-3">
        <div class="card-header">Load Data from SAP</div>
        <div class="card-body">
          <button id="btnPersno" type="button" class="btn btn-xl btn-info" onclick="updatePersno()">Null SAP Persno: {{ $nullpersno }}</button>
          <button id="btnOM" type="button" class="btn btn-xl btn-info" onclick="loadOM()">OM Data: {{ $eplist }}</button>
          <button id="btnOM" type="button" class="btn btn-xl btn-info" onclick="loadCuti()">Leave Data: {{ $cuticount }}</button>
          <div id="pbar" class="progress m-3" style="display: none">
            <div id="pbari" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%;">load persno</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-js')
<script type="text/javascript" defer>

  function loadCuti(){
    document.getElementById("btnOM").disabled = true;
    document.getElementById("pbar").style = "display: block";
    document.getElementById("pbari").text = "Updating OM info";

    var url = '{{ route("admin.loadDataCuti", [], false) }}';
    var aftercount = 0;

    $.ajax({
      url: url ,
      type: "GET",
      success: function(resp) {
        location.reload();
      },
      error: function(err) {
        alert(err.message);
      }
    });

  }

  function loadOM(){
    document.getElementById("btnOM").disabled = true;
    document.getElementById("pbar").style = "display: block";
    document.getElementById("pbari").text = "Updating OM info";

  }

  function updatePersno(){
    document.getElementById("btnPersno").disabled = true;
    document.getElementById("pbar").style = "display: block";
    document.getElementById("pbari").text = "mapping persno to staffs";

    var url = '{{ route("admin.updatePersno", [], false) }}';
    var aftercount = 0;

    $.ajax({
      url: url ,
      type: "GET",
      success: function(resp) {
        location.reload();
      },
      error: function(err) {
        alert(err.message);
      }
    });

    //
    // document.getElementById("btnPersno").innerHTML = "Null SAP Persno: " + aftercount;
    // document.getElementById("btnPersno").disabled = false;
    // document.getElementById("pbar").style = "display: none";
  }

</script>
@endsection
