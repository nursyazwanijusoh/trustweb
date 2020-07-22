@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Registered Projects</div>
        <div class="card-body">
          <div class="table-responsive">
            <a href="{{ route('opp.project.create')}}"><button class="btn btn-success mb-3"><i class="fa fa-plus"></i> Add Project</button></a>
            <table id="taskdetailtable" class="table table-striped table-bordered table-hover" >
              <thead>
                <tr>
                  <th scope="col">Project ID</th>
                  <th scope="col">Title</th>
                  <th scope="col">Project Manager</th>
                  <th scope="col">Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($pojeks as $acts)
                <tr title="{{ $acts->description }}">
                  <td>{{ $acts->project_no }}</td>
                  <td>{{ $acts->name }}</td>
                  <td>{{ $acts->Manager->name }}</td>
                  <td>{{ $acts->status }}</td>
                  <td class="text-center">
                    <a href="{{ route('opp.project.view', ['pid' => $acts->id ]) }}">
                      <button type="button" class="btn btn-sm btn-info" title="Info" ><i class="fa fa-info"></i></button>
                    </a>
                  </td>
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
<script type="text/javascript" defer>
$(document).ready(function() {
  $('#taskdetailtable').DataTable();
} );
</script>
@stop
