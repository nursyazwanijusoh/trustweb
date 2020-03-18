@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Staff with skill in {{ $be->name }}</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="taskdetailtable" class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Division</th>
                          <th scope="col">Staff Name</th>
                          <th scope="col">Competency</th>
                          <th scope="col">Status</th>
                          <th scope="col">Details</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($pss as $atask)
                        <tr>
                          <td>{{ $atask->User->unit }}</td>
                          <td><a href="{{ route('staff', ['id' => $atask->staff_id], false) }}">{{ $atask->User->name }}</a></td>
                          <td>{{ $atask->slevel() }}</td>
                          <td>{{ $atask->sStatus() }}</td>
                          <td><a href="{{ route('ps.detail', ['psid' => $atask->id])}}"><button type="button" class="btn btn-sm btn-info" title="Detail"><i class="fa fa-info"></i></button></a></td>
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
<script type="text/javascript">

$(document).ready(function() {
    $('#taskdetailtable').DataTable();
} );
</script>
@stop
