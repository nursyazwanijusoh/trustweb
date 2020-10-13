@extends('layouts.app')

@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-3">
                <div class="card-header">Staff Diary Details - Search Parameters</div>
                <div class="card-body">
                  <form onsubmit="fetch(); return false;">
                    <div class="form-group row">
                        <label for="fdate" class="col-md-3 col-form-label text-md-right">From</label>
                        <div class="col-md-6">
                          <input type="date" name="fdate" id="fdate" required />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="todate" class="col-md-3 col-form-label text-md-right">To</label>
                        <div class="col-md-6">
                          <input type="date" name="todate" id="todate" required />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="userlist" class="col-md-3 col-form-label text-md-right">Staff List</label>
                        <div class="col-md-9">
                          <select class="form-control" id="userlist" name="userlist" multiple required >
                            {{-- @foreach ($ulist as $act)
                            <option value="{{ $act->id }}" title="{{ $act->unit }}" >{{ $act->staff_no }} - {{ $act->name }}</option>
                            @endforeach --}}
                          </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Get Diary Details</button>
                            <!-- <button type="submit" class="btn btn-primary" name="subtype" value="gwd">Get GWD Activities Data</button> -->
                        </div>
                    </div>
                  </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
          <div class="card mb-3">
            <div class="card-header" id="headc">Report Data</div>
            <div class="card-body text-center">
              <div class="table-responsive">
                <table id="repothist" class="table table-bordered table-hover" style="white-space: nowrap;">
                  <thead>
                    <tr>
                      <th scope="col">Name</th>
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
  dtable.clear().draw();
  var values = $('#userlist').val();
  recsize = values.length;
  fdate = $('#fdate').val();
  tdate = $('#tdate').val();
  counter = 0;
  $('#headc').html("Report Data : " + counter + " / " + recsize);
  values.forEach(loadOneStaff);
}

function loadOneStaff(id){
  counter++;
  $('#headc').html("Report Data : " + counter + " / " + recsize);

  var search_url = "{{ route('reports.api.indiarept') }}";

  $.ajax({
    url: search_url,
    data: {
      'uid' : id,
      'startdate' : fdate,
      'enddate' : tdate
    },
    success: function(result) {
      dtable.rows.add(result).draw();
    },
    error: function(xhr){
      // alert("An error occured: " + xhr.status + " " + xhr.statusText);
    }
  });
}


$(document).ready(function() {

  $('#userlist').select2({
    minimumInputLength: 4,
    ajax: {
      url: "{{ route('webapi.s2findstaff') }}",
      data: function (params) {
        var query = {
          input: params.term
        }

        // Query parameters will be ?search=[term]&type=public
        return query;
      }
    }
  });

  dtable = $('#repothist').DataTable({
      paging: true,
      dom: 'Bfrtip',
      buttons: [
          'csv', 'excel'
      ],
      columns : [
        {data: 'name'},
        {data: 'date'},
        {data: 'tag'},
        {data: 'type'},
        {data: 'title'},
        {data: 'detail'},
        {data: 'hours'}
      ]
  });


});

</script>


@endsection
