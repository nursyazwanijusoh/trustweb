@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Pending Registration Approvals</div>
                <div class="card-body">
                  <table id="taskdetailtable" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Company</th>
                        <th scope="col">Staff No</th>
                        <th scope="col">Email</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($users as $atask)
                      <tr>
                        <td>{{ $atask->name }}</td>
                        <td>{{ $atask->Partner->comp_name }}</td>
                        <td>{{ $atask->staff_no }}</td>
                        <td>{{ $atask->email }}</td>
                        <td><a href="{{ route('admin.regapprove', ['staff_id' => $atask->id], false) }}">Approve</a>&nbsp;<a href="{{ route('admin.regreject', ['staff_id' => $atask->id], false) }}">Reject</a></td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
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
