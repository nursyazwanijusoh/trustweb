@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Staff with experience in {{ $be->name }}</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="taskdetailtable" class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Division</th>
                          <th scope="col">Staff Name</th>
                          <th scope="col">Position</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($be->Users as $atask)
                        <tr>
                          <td>{{ $atask->unit }}</td>
                          <td><a href="{{ route('staff', ['id' => $atask->id], false) }}">{{ $atask->name }}</a></td>
                          <td>{{ $atask->jobtype }}</td>
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
