@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header"></div>
                <div class="card-body">
                  @if (session()->has('alert'))
                  <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ session()->get('alert') }}</strong>
                  </div>
                  @endif

                </div>
              </div>
              <div class="card">
                <div class="card-header">Project List</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="projmeja" class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Proj ID</th>
                          <th scope="col">Descr</th>
                          <th scope="col">Start Date</th>
                          <th scope="col">End Date</th>
                          <th scope="col">Status</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($projs as $proj)
                      
                      <tr>
                      <td>{{$proj->id}}</td>
                      <td>{{$proj->descr}}</td>
                      <td>{{$proj->start_date}}</td>
                      <td>{{$proj->end_date}}</td>
                      <td>{{$proj->status}}</td>
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
    $('#projmeja').DataTable();
} );
</script>
@stop
