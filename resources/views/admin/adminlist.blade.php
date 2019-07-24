@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
              <div class="card">
                <div class="card-header">List of Admins</div>
                <div class="card-body">
                  <form method="POST" action="{{ route('admin.findst', [], false)}}">
                    @csrf
                  <table id="taskdetailtable" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Division</th>
                        <th scope="col">Role</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($users as $acts)
                      <tr>
                        <td>
                          <button type="submit" class="btn btn-link" name="staff_no" value="{{ $acts->staff_no }}">{{ $acts->name }}</button>
                        </td>
                        <td>{{ $acts->unit }}</td>
                        @if($acts->role == 0)
                        <td>Super Admin</td>
                        @else
                        <td>Floor Admin</td>
                        @endif
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  </form>
                  <div class="form-group row">
                      <div class="col text-center">
                          <a href="{{ route('admin.st', [], false) }}"><button type="button" class="btn btn-primary">Add admin?</button></a>
                      </div>
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
