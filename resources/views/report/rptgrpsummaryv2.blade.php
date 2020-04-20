@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center no-gutters">
      <div class="col-lg-12">
        <div class="card mb-3">
          <div class="card-body">
            <p class="mb-1">
              Generating data for {{ $groupname }} from {{ $startdate }} to {{ $enddate }}
            </p>
            <table>
              <tr>
                <td>Expected record : </td>
                <td>{{ sizeof($idlist) }}</td>
              </tr>
              <tr>
                <td>Fetched record : </td>
                <td id="fetchedrecord">0</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="card mb-3">
          <div class="card-header">Reports History</div>
          <div class="card-body text-center">
            <div class="table-responsive">
              <table id="repothist" class="table table-striped table-bordered table-hover" style="white-space: nowrap;">
                <thead>
                  <tr>
                    @foreach($header as $tf)
                    <th scope="col">{{ $tf }}</th>
                    @endforeach
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
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js "></script>

<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  dtable = $('#repothist').DataTable({
      paging: true,
      dom: 'Bfrtip',
      buttons: [
          'csv', 'excel'
      ]
  });

  counter = 0;
  var idlist = @json($idlist);

  idlist.forEach(loadOneStaff);

} );

function loadOneStaff(staffid){

  var search_url = "{{ route('report.gwd.api.person') }}";

  $.ajax({
    url: search_url,
    data: {
      'user_id' : staffid,
      'fdate' : "{{ $startdate }}",
      'tdate' : "{{ $enddate }}"
    },
    success: function(result) {
      dtable.row.add(result).draw();
      document.getElementById('fetchedrecord').innerHTML = ++counter;
    },
    error: function(xhr){
      document.getElementById('fetchedrecord').innerHTML = ++counter;
      // alert("An error occured: " + xhr.status + " " + xhr.statusText);
    }
  });
}
</script>
@stop
