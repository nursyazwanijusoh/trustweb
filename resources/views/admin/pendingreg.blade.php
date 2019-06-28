@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ $title }}</div>
                <div class="card-body">
                  <div class="row mb-0">
                      <div class="col">
                          @if($type == 'pending')
                          <a href="{{ route('admin.reglist', ['type' => 'active'], false) }}"><button class="btn btn-primary">Active Users</button></a>
                          <button class="btn btn-secondary">Pending Approval</button>
                          <a href="{{ route('admin.reglist', ['type' => 'email'], false) }}"><button class="btn btn-primary">Pending Email Verification</button></a>
                          @elseif($type == 'email')
                          <a href="{{ route('admin.reglist', ['type' => 'active'], false) }}"><button class="btn btn-primary">Active Users</button></a>
                          <a href="{{ route('admin.reglist', ['type' => 'pending'], false) }}"><button class="btn btn-primary">Pending Approval</button></a>
                          <button class="btn btn-secondary">Pending Email Verification</button>
                          @else
                          <button class="btn btn-secondary">Active Users</button>
                          <a href="{{ route('admin.reglist', ['type' => 'pending'], false) }}"><button class="btn btn-primary">Pending Approval</button></a>
                          <a href="{{ route('admin.reglist', ['type' => 'email'], false) }}"><button class="btn btn-primary">Pending Email Verification</button></a>
                          @endif
                      </div>
                  </div>
                  <br />
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
                        @if($type == 'pending')
                        <td><a href="{{ route('admin.regapprove', ['staff_id' => $atask->id], false) }}">Approve</a>&nbsp;<a href="{{ route('admin.regreject', ['staff_id' => $atask->id], false) }}">Reject</a></td>
                        @elseif($type == 'email')
                        <td><a href="{{ route('verification.resend', ['staff' => $atask->id], false) }}">Resend</a>&nbsp;<a href="{{ route('admin.regreject', ['staff_id' => $atask->id], false) }}">Reject</a></td>
                        @else
                        <td><a href="{{ route('admin.delstaff', ['staff_id' => $atask->id], false) }}">Delete</a></td>
                        @endif
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
