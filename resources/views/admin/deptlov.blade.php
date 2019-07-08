@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Scan LDAP for updated list of departments</div>
                @if(isset($err))
                <div class="alert alert-warning" role="alert">{{ $err }}</div>
                @endif
                <div class="card-body">
                  <p>
                    Note: This will go through all LDAP directories to fetch current data. So, it will take a while to complete
                  </p>
                  <form method="POST" action="{{ route('admin.reflov', [], false) }}">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col text-center">
                            <button type="submit" class="btn btn-primary">Refresh Div Name</button>
                        </div>
                    </div>
                  </form>
                  <br />
                  <form method="POST" action="{{ route('admin.upstaffdiv', [], false) }}">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col text-center">
                            <button type="submit" class="btn btn-primary">Update Staff's Division Info</button>
                        </div>
                    </div>
                  </form>
                </div>
            </div>
            <br />
            <div class="card">
              <div class="card-header">Allowed Departments</div>
              <div class="card-body">
                <table id="allowedtable" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th scope="col">Name</th>
                      <th scope="col">Reg. Staff Count</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($allowedunits as $acts)
                    <tr>
                      <td>{{ $acts->pporgunitdesc }}</td>
                      <td>{{ $acts->Staffs->count() }}</td>
                      <td><a href="{{ route('admin.blockdiv', [$acts->id], false) }}">Block</a></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <br />
            <div class="card">
              <div class="card-header">The rest of the departments</div>
              <div class="card-body">
                <table id="blockedtable" class="table table-striped table-bordered table-hover">
                  <thead>
                    <th scope="col">Name</th>
                    <th scope="col">Reg. Staff Count</th>
                    <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($blockedunits as $acts)
                    <tr>
                      <td>{{ $acts->pporgunitdesc }}</td>
                      <td>{{ $acts->Staffs->count() }}</td>
                      <td><a href="{{ route('admin.allowdiv', [$acts->id], false) }}">Allow</a></td>
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
    $('#allowedtable').DataTable();

    $('#blockedtable').DataTable();

} );
</script>
@stop
