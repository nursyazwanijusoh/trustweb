@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
          <div class="card">
            <div class="card-header">Add new Skill</div>
            @if(isset($alert))
            <div class="alert alert-success" role="alert">{{ $alert }}</div>
            @endif
            <div class="card-body">
              <table id="taskdetailtable" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th scope="col">Skill Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Created By</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($sklist as $acts)
                  <tr>
                    <td>{{ $acts->name }}</td>
                    <td>{{ $acts->SkillCategory->name }}</td>
                    <td>{{ $acts->creator->name }}</td>
                    <td>
                      <a href="{{ route('ps.do.add', ['skill_id' => $acts->id], false)}}">Add</a>
                      <a href="{{ route('troll', [], false)}}">Delete</a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Cant find the skill? Create one</div>
            <div class="card-body">
              <form method="POST" action="{{ route('ps.create', [], false) }}">
                @csrf
                <div class="form-group row">
                  <label for="name" class="col-md-4 col-form-label text-md-right">Skill Name</label>
                  <div class="col-md-6">
                    <input id="name" type="text" class="form-control" name="name" required  />
                  </div>
                </div>
                <div class="form-group row">
                  <label for="scat" class="col-md-4 col-form-label text-md-right">Skill Category</label>
                  <div class="col-md-6">
                    <select class="form-control" id="scat" name="scat" required>
                      @foreach ($cats as $atask)
                      <option value="{{ $atask['id'] }}" >{{ $atask['name'] }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                      <button type="submit" class="btn btn-primary">Create Skill</button>
                    </div>
                </div>
              </form>
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
