@extends('layouts.app')

@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">Group Analysis - Search Parameters</div>
                <div class="card-body">
                  <form action="{{ route('report.gwd.grpanalysis')}}" method="get">
                    <div class="form-group row">
                        <label for="fdate" class="col-md-3 col-form-label text-md-right">Record Month</label>
                        <div class="col-md-6">
                          <input type="date" name="fdate" id="fdate" value="{{ $indate }}" required />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="userlist" class="col-md-3 col-form-label text-md-right">Group</label>
                        <div class="col-md-7">
                          <select class="form-control" id="userlist" name="gid" required >
                            @foreach ($gplist as $act)
                            <option value="{{ $act->id }}" @if($act->id == $gid) selected @endif >{{ $act->name }}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                          <div id="baten">
                            <button type="submit" class="btn btn-primary">Generate Analysis</button>
                          </div>
                        </div>
                    </div>
                  </form>
                </div>
            </div>
        </div>
        @if($gotdata == true)
        <div class="col-lg-12">
          <div class="card mb-3">
            <div class="card-header" id="headc">Report Data</div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="repothist" class="table table-bordered table-hover" style="white-space: nowrap;">
                  <thead>
                    <tr>
                      <th scope="col">Staff No</th>
                      <th scope="col">Name</th>
                      <th scope="col">Band</th>
                      <th scope="col">Division</th>
                      <th scope="col">Report Month</th>
                      <th scope="col">Working &gt; 12 hrs</th>
                      <th scope="col">Work during AL/MC</th>
                      <th scope="col">Work on Sat/Sun</th>
                      <th scope="col">Total entries</th>
                      <th scope="col">Days with entries</th>
                      <th scope="col">Days with single entries</th>
                      <th scope="col">Single entry &gt; 4 hrs</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
@if($gotdata == true)
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js "></script>

<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script type="text/javascript">

function fetch(){
  // dtable.clear().draw();
  $("#baten").attr('class', 'd-none');
  var values = @json($users) ;
  recsize = values.length;
  counter = 0;
  errcount = 0;
  $('#headc').html("Report Data : " + counter + " / " + recsize + ". Error count: " + errcount);
  values.forEach(loadOneStaff);
  $("#baten").attr('class', '');
}

function loadOneStaff(id){
  counter++;
  var search_url = "{{ route('reports.api.indianal') }}";

  $.ajax({
    url: search_url,
    async: false,
    data: {
      'uid' : id,
      'mon' : "{{ $indate }}"
    },
    success: function(result) {
      dtable.row.add(result).draw();
    },
    error: function(xhr){
      errcount++;
      // alert("An error occured: " + xhr.status + " " + xhr.statusText);
    }
  });

  $('#headc').html("Report Data : " + counter + " / " + recsize + ". Error count: " + errcount);
}


$(document).ready(function() {

  $('#userlist').select2();

  dtable = $('#repothist').DataTable({
      paging: true,
      dom: 'Bfrtip',
      buttons: [
          'excelHtml5', 'csvHtml5'
      ],
      columns : [
        {data: 'staff_no'},
        {data: 'name'},
        {data: 'band'},
        {data: 'division'},
        {data: 'rptmon'},
        {data: 'w12h'},
        {data: 'walmc'},
        {data: 'wwend'},
        {data: 'ecount'},
        {data: 'dwentries'},
        {data: 'd1entry'},
        {data: 'em4h'}
      ]
  });


  fetch();
});

</script>
@else
<script type="text/javascript">
$(document).ready(function() {

  $('#userlist').select2();

});
</script>
@endif

@endsection
