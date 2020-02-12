@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Find Staff</div>
                <div class="card-body">
                  <form method="GET" action="{{ route('staff.find', [], false) }}" id="whform">
                    <!-- @csrf -->
                    <!-- <h5 class="card-title">Date range</h5> -->
                    <div class="form-group row">
                      <label for="sinput" class="col-md-4 col-form-label text-md-right">Name / Staff No</label>
                      <div class="col-md-6">
                        <input id="sinput" class="form-control" type="text" name="input" required minlength="3" autofocus>
                      </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                  </form>
                </div>
              </div>
                @if($result != 'empty')
              <div class="card">
                @if($result == '404')
                <div class="alert alert-error" role="alert">No result</div>
                @else
                <div class="card-header"> </div>
                <div class="card-body">
                  <!-- <h5 class="card-title">List of task type</h5> -->
                  <div class="table-responsive">
                  <table id="taskdetailtable" class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Staff ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Division</th>
                        <th scope="col">Email</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($result as $atask)
                      <tr>
                        <td><a href="{{ route('staff', ['staff_id' => $atask->id], false) }}">{{ $atask->staff_no }}</a></td>
                        <td>{{ $atask->name }}</td>
                        <td>{{ $atask->unit }}</td>
                        <td>{{ $atask->email }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th scope="col">Staff ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Division</th>
                        <th scope="col">Email</th>
                      </tr>
                    </tfoot>
                  </table>
                  </div>
                </div>
                @endif
              </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@if($result != 'empty' && $result != '404')
@section('page-js')
<!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" defer>
$(document).ready(function() {
    $('#taskdetailtable').DataTable();
} );
</script>
@endsection
@endif
