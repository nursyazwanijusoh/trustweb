@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">Add new Division Group</div>
                <div class="card-body">
                  @if(session()->has('alert'))
                  <div class="alert alert-info" role="alert">{{ session()->get('alert') }}</div>
                  @endif
                  <form method="POST" action="{{ route('cgrp.add', [], false) }}">
                    @csrf
                    <!-- <h5 class="card-title">Add new avatar</h5> -->
                    <div class="form-group row">
                        <label for="incode" class="col-md-4 col-form-label text-md-right">Group Name</label>
                        <div class="col-md-6">
                          <input id="incode" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" value="{{ old('code') }}" type="text" name="code" maxlength="50" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Create Group</button>
                        </div>
                    </div>
                  </form>
                </div>
              </div><br />
              <div class="card">
                <div class="card-header">List of Groups</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="fblist" class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Group Name</th>
                          <th scope="col">Total Staff Count</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($data as $atask)
                        <tr>
                          <td>{{ $atask->name }}</td>
                          <td>{{ $atask->StaffCount() }}</td>
                          <td>
                            <form action="{{ route('cgrp.del', [], false) }}" method="post">
                              @csrf
                              <input type="hidden" name="id" value="{{ $atask->id }}" />
                              <a href="{{ route('cgrp.view', ['id' => $atask->id], false) }}">
                                <button type="button" class="btn btn-success btn-sm" title="View">View</button>
                              </a>
                              <button type="submit" class="btn btn-danger btn-sm" onsubmit="return confirm('Confirm delete?')" title="Delete">Delete</button>
                            </form>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
            </div><br />
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">

$(document).ready(function() {
    $('#fblist').DataTable();
} );

</script>

@stop
