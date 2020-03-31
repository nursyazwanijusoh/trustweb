@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">Change group name</div>
                <div class="card-body">
                  @if(session()->has('alert'))
                  <div class="alert alert-info" role="alert">{{ session()->get('alert') }}</div>
                  @endif
                  <form method="POST" action="{{ route('cgrp.edit', [], false) }}">
                    @csrf
                    <!-- <h5 class="card-title">Add new avatar</h5> -->
                    <input type="hidden" name="id" value="{{ $cgrp->id }}" />
                    <div class="form-group row">
                        <label for="incode" class="col-md-4 col-form-label text-md-right">Group Name</label>
                        <div class="col-md-6">
                          <input id="incode" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" value="{{ old('code', $cgrp->name) }}" type="text" name="code" maxlength="50" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Rename</button>
                        </div>
                    </div>
                  </form>
                </div>
              </div><br />
              <div class="card">
                <div class="card-header">Group Members</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="glist01" class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Name</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($cgrp->Members as $atask)
                        <tr>
                          <td>{{ $atask->pporgunit }} - {{ $atask->pporgunitdesc }}</td>
                          <td>
                            <form action="{{ route('cgrp.remove', [], false) }}" method="post">
                              @csrf
                              <input type="hidden" name="id" value="{{ $atask->id }}" />
                              <input type="hidden" name="gid" value="{{ $cgrp->id }}" />
                              <button type="submit" class="btn btn-warning btn-sm" title="Remove">Remove</button>
                            </form>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
            </div><br />
            <div class="card">
              <div class="card-header">Units not in any group</div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="glist02" class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($freeg as $atask)
                      <tr>
                        <td>{{ $atask->pporgunit }} - {{ $atask->pporgunitdesc }}</td>
                        <td>
                          <form action="{{ route('cgrp.take', [], false) }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $atask->id }}" />
                            <input type="hidden" name="gid" value="{{ $cgrp->id }}" />
                            <button type="submit" class="btn btn-info btn-sm" title="Take">Take</button>
                          </form>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
          </div><br />
          <div class="card">
            <div class="card-header">Units that belongs to other group</div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="glist03" class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th scope="col">Name</th>
                      <th scope="col">Group</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($otherg as $atask)
                    <tr>
                      <td>{{ $atask->pporgunit }} - {{ $atask->pporgunitdesc }}</td>
                      <td>{{ $atask->Group->name }}</td>
                      <td>
                        <form action="{{ route('cgrp.take', [], false) }}" method="post">
                          @csrf
                          <input type="hidden" name="id" value="{{ $atask->id }}" />
                          <input type="hidden" name="gid" value="{{ $cgrp->id }}" />
                          <button type="submit" class="btn btn-danger btn-sm" title="Take">Take</button>
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
    $('#glist01').DataTable();
    $('#glist02').DataTable();
    $('#glist03').DataTable();
} );

</script>

@stop
