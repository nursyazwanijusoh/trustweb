@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Work Place Management</div>
                @if($role == 0)
                <div class="card-body">
                  <form method="POST" action="{{ route('admin.addbuild', [], false) }}">
                    @csrf
                    <h5 class="card-title">Add new Office Floor</h5>

                    <div class="form-group row">
                        <label for="building_name" class="col-md-4 col-form-label text-md-right">Office Building</label>
                        <div class="col-md-6">
                          <select class="form-control" id="building_name" name="office_id" required>
                            @foreach ($office as $atask)
                            <option value="{{ $atask['id'] }}">{{ $atask['building_name'] }}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="floor_name" class="col-md-4 col-form-label text-md-right">Floor name</label>
                        <div class="col-md-6">
                            <input id="floor_name" class="form-control" type="text" name="floor_name" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="unit" class="col-md-4 col-form-label text-md-right">Unit</label>
                        <div class="col-md-6">
                            <input id="unit" class="form-control" type="text" name="unit" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="remark" class="col-md-4 col-form-label text-md-right">Remark</label>
                        <div class="col-md-6">
                          <textarea rows="3" class="form-control" id="remark" name="remark"></textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                  </form>
                </div>
                @endif
                <!-- <div class="card-header"> </div> -->
                <div class="card-body">
                  <h5 class="card-title">List of work space</h5>
                  <table id="taskdetailtable" class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Building</th>
                        <th scope="col">Floor</th>
                        <th scope="col">Unit</th>
                        <th scope="col">Remark</th>
                        <th scope="col">Added By</th>
                        @if($role == 0)
                        <th scope="col">Action</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($buildlist as $atask)
                      <tr>
                        <td>{{ $atask['building_name'] }}</td>
                        <td><a title="{{ $atask->place->count() . ' seats, ' . $atask->MeetingRooms->count() . ' meeting rooms.' }}" href="{{ route('admin.buildetail', ['build_id' => $atask['id']], false) }}">{{ $atask['floor_name'] }}</a></td>
                        <td>{{ $atask['unit'] }}</td>
                        <td>{{ $atask['remark'] }}</td>
                        <td>{{ $atask['created_by'] }}</td>
                        @if($role == 0)
                        <td><a href="{{ route('admin.delbuild', ['build_id' => $atask['id']], false) }}">Remove</a></td>
                        @endif
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
    $('#taskdetailtable').DataTable();
} );
</script>
@stop
