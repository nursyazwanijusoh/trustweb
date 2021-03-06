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
                        @foreach($header as $ap)
                        <th scope="col">{{ $ap }}</th>
                        @endforeach
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($result as $atask)
                      <tr>
                        <td>{{ $atask->unit }}</td>
                        <td><a href="{{ route('ps.list', ['staff_id' => $atask->id], false) }}">{{ $atask->name }}</a></td>
                        <td>{{ $atask->jobtype }}</td>
                        @foreach($skillids as $sid)
                        <td>{{ $atask->GetSkillLevel($sid) }}</td>
                        @endforeach
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
