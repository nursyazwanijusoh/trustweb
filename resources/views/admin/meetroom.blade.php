@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Meeting Rooms Management</div>
                <div class="card-body">
                  @if(isset($alert))
                  <div class="alert alert-info" role="alert">{{ $alert }}</div>
                  @endif
                  <form method="POST" action="{{ route('admin.meetroom.add', [], false) }}">
                    @csrf
                    <h5 class="card-title">Add meeting room</h5>
                    <div class="form-group row">
                        <label for="building_name" class="col-md-4 col-form-label text-md-right">Office Floor</label>
                        <div class="col-md-6">
                          <select class="form-control" id="building_name" name="building_id" required>
                            @foreach ($buildings as $atask)
                            <option value="{{ $atask['id'] }}">{{ $atask['floor_name'] . ', ' . $atask['building_name'] }}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Room Label / Name</label>
                        <div class="col-md-6">
                            <input id="name" class="form-control" type="text" name="name" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">QR data</label>
                        <div class="col-md-6">
                            <input id="name" class="form-control" type="text" name="qrdata" required >
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Add Room</button>
                        </div>
                    </div>
                  </form>
                </div>
                <div class="card-header"> </div>
                <div class="card-body">
                  <h5 class="card-title">List of Meeting Rooms</h5>
                  <table id="fblist" class="table table-striped table-hover table-responsive">
                    <thead>
                      <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Location</th>
                        <th scope="col">Current Occupancies</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $atask)
                      <tr>
                        <td>{{ $atask->label }}</td>
                        <td>{{ $atask->building->floor_name . ', ' . $atask->building->building_name }}</td>
                        <td>{{ $atask->Checkin->count() }}</td>
                        <td>
                          <a href="{{ route('admin.getqr', ['seat_id' => $atask['id']], false) }}">
                            <button type="button" class="btn btn-info btn-sm" title="View QR">Get QR</button>
                          </a>
                          <button id="btnedit" type="button" class="btn btn-warning btn-sm" title="Edit"
                          data-toggle="modal" data-target="#editCfgModal"
                          data-id="{{$atask['id']}}" data-name="{{$atask['label']}}"
                          data-qrdata="{{$atask['qr_code']}}" data-buildid="{{$atask['building_id']}}"
                          >Edit</button>
                          <a href="{{ route('admin.meetroom.del', ['id' => $atask['id']], false) }}">
                            <button type="button" class="btn btn-danger btn-sm" title="Delete">Delete</button>
                          </a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editCfgModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit Meeting Room</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('admin.meetroom.edit', [], false) }}">
              @csrf
              <div class="modal-body">
                <input type="hidden" value="0" name="id" id="edit-id" />
                <div class="form-group row">
                    <label for="edit-building" class="col-md-4 col-form-label text-md-right">Office Floor</label>
                    <div class="col-md-6">
                      <select class="form-control" id="edit-building" name="building_id" required>
                        @foreach ($buildings as $atask)
                        <option value="{{ $atask['id'] }}">{{ $atask['floor_name'] . ', ' . $atask['building_name'] }}</option>
                        @endforeach
                      </select>
                    </div>
                </div>
                <div class="form-group row">
                  <label for="edit-name" class="col-sm-4 col-form-label text-sm-right">Name / Label</label>
                  <input type="text" class="form-control col-sm-6" id="edit-name" name="name" required>
                </div>
                <div class="form-group row">
                  <label for="edit-qrdata" class="col-sm-4 col-form-label text-sm-right">QR data</label>
                  <input type="text" class="form-control col-sm-6" id="edit-qrdata" name="qrdata" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@section('page-js')
<script type="text/javascript">

$('#editCfgModal').on('show.bs.modal', function(e) {

    //get data-id attribute of the clicked element
    var id = $(e.relatedTarget).data('id');
    var name = $(e.relatedTarget).data('name');
    var qrdata = $(e.relatedTarget).data('qrdata');
    var buildid = $(e.relatedTarget).data('buildid');

    //populate the textbox
    document.getElementById("edit-id").value = id;
    document.getElementById("edit-name").value = name;
    document.getElementById("edit-qrdata").value = qrdata;
    document.getElementById("edit-building").value = buildid;
});
</script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" defer>
$(document).ready(function() {
    $('#fblist').DataTable();
} );
</script>
@stop
