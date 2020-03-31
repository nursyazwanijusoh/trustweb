@extends('layouts.app')

@section('page-css')
<link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-11">
          <div class="card mb-3">
              <div class="card-header">Search Parameters</div>
              <div class="card-body">
                <div class="row no-gutters">
                  @foreach($paramskill as $ap)
                  <div class="col-auto">
                    <div class="card bg-dark text-white m-1">
                      <div class="card-body p-2">
                         <p class="card-text">{{ $ap }}</p>
                      </div>
                    </div>
                  </div>
                  @endforeach
                  @foreach($paramexp as $ap)
                  <div class="col-auto">
                    <div class="card bg-light m-1">
                      <div class="card-body p-2">
                         <p class="card-text">{{ $ap }}</p>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
          </div>
      </div>
      <div class="col-md-11">
          <div class="card">
              <div class="card-header">Search result</div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="taskdetailtable" class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Division</th>
                        <th scope="col">Name</th>
                        <th scope="col">Report To</th>
                        <th scope="col">Skill</th>
                        <th scope="col">Competency</th>
                        <th scope="col">Approved Competency</th>
                        <th scope="col">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($psres as $atask)
                      <tr>
                        <td>{{ $atask['division'] }}</td>
                        <td><a href="{{ route('staff', ['staff_id' => $atask['staff_id']], false) }}">{{ $atask['name'] }}</a></td>
                        <td><a href="{{ route('staff', ['staff_id' => $atask['report_to_id']], false) }}">{{ $atask['report_to_name'] }}</a></td>
                        <td><a href="{{ route('ps.detail', ['psid' => $atask['ps_id']], false) }}">{{ $atask['ps_name'] }}</a></td>
                        <td>{{ $atask['ps_level'] }}</td>
                        <td>{{ $atask['ps_plevel'] }}</td>
                        <td>{{ $atask['ps_status'] }}</td>
                      </tr>
                      @endforeach
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
    $('#taskdetailtable').DataTable({
        paging: true,
        dom: 'Bfrtip',
        buttons: [
            'excel'
        ]
    });
} );
</script>
@stop
