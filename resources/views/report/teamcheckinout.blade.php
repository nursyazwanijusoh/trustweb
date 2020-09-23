@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card mb-3">
          <div class="card-header">Team Check-in Report</div>
          <div class="card-body>">
            <form method="GET" action="{{ route('reports.c.detail', [], false) }}">
              <input type="hidden" name="uid" value="{{ $uid }}" />
              <div class="form-group row mt-3">
                  <label for="fdate" class="col-md-4 col-form-label text-md-right">From</label>
                  <div class="col-md-7">
                    <input type="date" class="form-control{{ $errors->has('fdate') ? ' is-invalid' : '' }}" name="fdate" id="fdate" value="{{ old('fdate', $sdate) }}" required />
                    @if ($errors->has('fdate'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('fdate') }}</strong>
                        </span>
                    @endif
                  </div>
              </div>
              <div class="form-group row">
                  <label for="tdate" class="col-md-4 col-form-label text-md-right">To</label>
                  <div class="col-md-7">
                    <input type="date" class="form-control{{ $errors->has('tdate') ? ' is-invalid' : '' }}" name="tdate" id="tdate" value="{{ old('tdate', $edate) }}" required />
                    @if ($errors->has('tdate'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('tdate') }}</strong>
                        </span>
                    @endif
                  </div>
              </div>
              <div class="form-group row mb-0">
                  <div class="col text-center">
                      <button type="submit" class="btn btn-primary m-1">Generate Report</button>
                  </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      @if($gotrpt == true)
      <div class="col-lg-6">
        <div class="card mb-3">
          <div class="card-body">
            <p class="mb-1">
              Generating check-in data for <b>{{ $groupname }}</b> from {{ $sdate }} to {{ $edate }}
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
            <p class="mb-1">
              Report will display earliest check-in and latest check-out time
            </p>
          </div>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="card mb-3">
          <div class="card-header">Report Data</div>
          <div class="card-body text-center">
            <div class="table-responsive">
              <table id="repothist" class="table table-bordered table-hover" style="white-space: nowrap;">
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
      @endif
    </div>
</div>
<a href="{{ route('staff', ['user_id' => ''])}}"></a>
@endsection

@if($gotrpt == true)
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
      ],
      columns : [
        {
          data: 'staff',
          render: function(data, type, row){
            return '<a href="{{ route('staff', ['staff_id' => ''])}}'+data.id+'">'+data.name+'</a>';
          }
        },
        {data: 'staff_no'},
        {data: 'unit'},
        {data: 'teamab'},
        @foreach($dtablerender as $one)
        {data: '{{ $one }}_cuti'},
        {data: '{{ $one }}_chin'},
        {data: '{{ $one }}_chout'},
        {data: '{{ $one }}_clin'},
        {data: '{{ $one }}_clout'},
        @endforeach
      ]
  });

  counter = 0;
  var idlist = @json($idlist);

  // loadOneStaff(1);

  idlist.forEach(loadOneStaff);

} );

function loadOneStaff(staffid){

  var search_url = "{{ route('report.cekin.api.person') }}";

  $.ajax({
    url: search_url,
    data: {
      'user_id' : staffid,
      'fdate' : "{{ $sdate }}",
      'tdate' : "{{ $edate }}"
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
@endsection
@endif
