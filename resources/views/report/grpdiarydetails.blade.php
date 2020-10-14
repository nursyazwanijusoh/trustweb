@extends('layouts.app')

@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card mb-3">
          <div class="card-body">
            <p class="mb-1">
              Generating diary details data for {{ $groupname }} from {{ $startdate }} to {{ $enddate }}
            </p>
          </div>
        </div>
      </div>
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
                      <th scope="col">Date</th>
                      <th scope="col">Act Tag</th>
                      <th scope="col">Act Type</th>
                      <th scope="col">ID/Title</th>
                      <th scope="col">Details</th>
                      <th scope="col">Hours</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js "></script>

<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script type="text/javascript">

function fetch(){

  // dtable.clear().draw();
  var values = @json($idlist);
  recsize = values.length;
  counter = 0;
  errcount = 0;
  $('#headc').html("Report Data : " + counter + " / " + recsize + ". Error count: " + errcount);
  values.forEach(loadOneStaff);
}

function loadOneStaff(id){
  counter++;
  var search_url = "{{ route('reports.api.indiarept') }}";

  $.ajax({
    url: search_url,
    data: {
      'uid' : id,
      'startdate' : "{{ $startdate }}",
      'enddate' : "{{ $enddate }}"
    },
    success: function(result) {
      dtable.rows.add(result).draw();
    },
    error: function(xhr){
      errcount++;
      // alert("An error occured: " + xhr.status + " " + xhr.statusText);
    }
  });

  $('#headc').html("Report Data : " + counter + " / " + recsize + ". Error count: " + errcount);
}


$(document).ready(function() {

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
        {data: 'date'},
        {data: 'tag'},
        {data: 'type'},
        {data: 'title'},
        {data: 'detail'},
        {data: 'hours'}
      ]
  });

  fetch();

});

</script>


@endsection
