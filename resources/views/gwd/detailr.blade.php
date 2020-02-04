@extends('layouts.app')

@section('page-css')
<link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Diary Entry for {{ $rlabel }}</div>
                <div class="card-body">
                  <h5 class="card-title">Total Expected Hours: {{ $expthours }}</h5>
                  <div class="table-responsive">
                    <table id="taskdetailtable" class="table table-hover table-bordered">
                      <thead>
                        <tr>
                          @foreach($header as $ah)
                          @if($ah['isweekend'] == 'y')
                          <th class="table-dark">{{ $ah['date'] }}</th>
                          @elseif($ah['isweekend'] == 'n')
                          <th class="table-success">{{ $ah['date'] }}</th>
                          @else
                          <th>{{ $ah['date'] }}</th>
                          @endif
                          @endforeach
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($staffs as $acts)
                        <tr>
                          <th scope="col" >
                            <a href="{{ route('reports.staff.drs', [
                                'staff_no' => $acts['staff_no'],
                                'fromdate' => $fromdate,
                                'todate' => $todate
                              ], false) }}">{{ $acts['name'] }}</a>
                          </th>
                          @foreach($acts['hours'] as $mds)
                          <td>{{ $mds }}</td>
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

<script type="text/javascript" defer>
$(document).ready(function() {
    $('#taskdetailtable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel'
        ]
    });
} );
</script>
@stop
