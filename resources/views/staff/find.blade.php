@extends('layouts.app')

@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-3">
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
              <div class="card mb-3">
                  <div class="card-header">Find Staff With Skill</div>
                  <div class="card-body">
                    <form method="post" action="{{ route('staff.skill.find', [], false) }}" id="whform">
                      @csrf
                      <!-- <h5 class="card-title">Date range</h5> -->

                      <div class="form-group row">
                        <label for="csid" class="col-md-4 col-form-label text-md-right">Skillset</label>
                        <div class="col-md-6">
                          <select class="form-control" id="skid" name="skid[]" multiple >
                            @foreach ($skills as $act)
                            <option value="{{ $act->id }}" title="{{ $act->name }}" >{{ $act->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="csid" class="col-md-4 col-form-label text-md-right">Experience</label>
                        <div class="col-md-6">
                          <select class="form-control" id="expid" name="expid[]" multiple >
                            @foreach ($exps as $act)
                            <option value="{{ $act->id }}" title="{{ $act->name }}" >{{ $act->name }}</option>
                            @endforeach
                          </select>
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

@section('page-js')
<!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#taskdetailtable').DataTable();

    $('#skid').select2({
        width: '100%'
    });

    $('#expid').select2({
        width: '100%'
    });
} );
</script>
@endsection
