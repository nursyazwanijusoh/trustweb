@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card mb-3">
        <div class="card-header">8 Hours on friday</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="lapan" class="table table-striped table-hover">
              <thead>
                <tr>
                  <th scope="col">Org Unit</th>
                  <th scope="col">Description</th>
                  <th scope="col">Staff Count</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($lapan as $atask)
                <tr>
                  <td>{{ $atask->pporgunit }}</td>
                  <td>{{ $atask->pporgunitdesc }}</td>
                  <td>{{ $atask->Staffs->count() }}</td>
                  <td>
                    <form action="{{ route('admin.delfriday8')}}" method="post">
                      @csrf
                      <input type="hidden" name="id" value="{{ $atask->id }}" />
                      <button type="submit" class="btn btn-success btn-sm" title="Set to 7.5"><i class="fa fa-level-down"></i></button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card mb-3">
        <div class="card-header">7.5 Hours on Friday</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="tujuh" class="table table-striped table-hover">
              <thead>
                <tr>
                  <th scope="col">Action</th>
                  <th scope="col">Org Unit</th>
                  <th scope="col">Description</th>
                </tr>
              </thead>
              <tbody>
                @foreach($tujuh as $atask)
                <tr>
                  <td>
                    <form action="{{ route('admin.addfriday8')}}" method="post">
                      @csrf
                      <input type="hidden" name="id" value="{{ $atask->id }}" />
                      <button type="submit" class="btn btn-success btn-sm" title="Set to 8"><i class="fa fa-reply"></i></button>
                    </form>
                  </td>
                  <td>{{ $atask->pporgunit }}</td>
                  <td>{{ $atask->pporgunitdesc }}</td>
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
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">

$(document).ready(function() {
    $('#lapan').DataTable();
} );

$(document).ready(function() {
    $('#tujuh').DataTable();
} );

</script>

@stop
