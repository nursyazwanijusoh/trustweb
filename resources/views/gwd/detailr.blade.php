@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Diary Entry for {{ $rlabel }}</div>
                <div class="card-body">
                  <h5 class="card-title">Total Expected Hours: {{ $expthours }}</h5>
                  <div class="table-responsive">
                    <table id="taskdetailtable" class="table table-striped table-hover table-bordered">
                      <thead>
                        <tr>
                          @foreach($header as $ah)
                          @if($ah['isweekend'] == 'y')
                          <td class="table-dark" style="white-space:nowrap;">{{ $ah['date'] }}</td>
                          @elseif($ah['isweekend'] == 'n')
                          <td class="table-success" style="white-space:nowrap;">{{ $ah['date'] }}</td>
                          @else
                          <td style="white-space:nowrap;">{{ $ah['date'] }}</td>
                          @endif
                          @endforeach
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($staffs as $acts)
                        <tr>
                          <th scope="col" style="white-space:nowrap;">
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
<!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" defer>
$(document).ready(function() {
    $('#taskdetailtable').DataTable();
} );
</script>
@stop
