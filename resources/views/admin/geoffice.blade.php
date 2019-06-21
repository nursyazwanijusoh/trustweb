@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Office Building Management</div>
                <div class="card-body">
                  @if(isset($alert))
                  <div class="alert alert-info" role="alert">{{ $alert }}</div>
                  @endif
                  <form method="POST" action="{{ route('geo.add', [], false) }}">
                    @csrf
                    <h5 class="card-title">Add new building location?</h5>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Building name</label>
                        <div class="col-md-6">
                            <input id="name" class="form-control" type="text" name="building_name" required autofocus>
                        </div>
                    </div>

                    <h5 class="card-title">Point A Coordinate <a data-toggle="tooltip" title="<img src=&quot;{{ asset('img/geo_guide.png')}}&quot;/>"><span class="badge badge-info">?</span></a></h5>
                    <div class="form-group row">
                        <label for="a_latitude" class="col-md-4 col-form-label text-md-right">Latitude</label>
                        <div class="col-md-6">
                          <input id="a_latitude" class="form-control" type="text" name="a_latitude" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="a_longitude" class="col-md-4 col-form-label text-md-right">Longitude</label>
                        <div class="col-md-6">
                          <input id="a_longitude" class="form-control" type="text" name="a_longitude" required>
                        </div>
                    </div>

                    <h5 class="card-title">Point B Coordinate <a data-toggle="tooltip" title="<img src=&quot;{{ asset('img/geo_guide.png')}}&quot;/>"><span class="badge badge-info">?</span></a></h5>
                    <div class="form-group row">
                        <label for="b_latitude" class="col-md-4 col-form-label text-md-right">Latitude</label>
                        <div class="col-md-6">
                          <input id="b_latitude" class="form-control" type="text" name="b_latitude" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="b_longitude" class="col-md-4 col-form-label text-md-right">Longitude</label>
                        <div class="col-md-6">
                          <input id="b_longitude" class="form-control" type="text" name="b_longitude" required>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Add Building</button>
                        </div>
                    </div>
                  </form>
                </div>
                <div class="card-header"> </div>
                <div class="card-body">
                  <h5 class="card-title">List of Office Buildings</h5>
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Point A</th>
                        <th scope="col">Point B</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $atask)
                      <tr>
                        <td>{{ $atask['building_name'] }}</td>
                        <td><a href="https://www.google.com/maps/search/?api=1&query={{ $atask['a_latitude'] . ',' . $atask['a_longitude'] }}" target="_blank">{{ $atask['a_latitude'] . ',' . $atask['a_longitude'] }}</a></td>
                        <td><a href="https://www.google.com/maps/search/?api=1&query={{ $atask['b_latitude'] . ',' . $atask['b_longitude'] }}" target="_blank">{{ $atask['b_latitude'] . ',' . $atask['b_longitude'] }}</a></td>
                        <td>
                          <button id="btnedit" type="button" class="btn btn-warning btn-sm" title="Edit"
                          data-toggle="modal" data-target="#editCfgModal"
                          data-id="{{$atask['id']}}" data-name="{{$atask['building_name']}}"
                          data-alat="{{$atask['a_latitude']}}"
                          data-along="{{$atask['a_longitude']}}"
                          data-blat="{{$atask['b_latitude']}}"
                          data-blong="{{$atask['b_longitude']}}"
                          >Edit</button>
                          <a href="{{ route('geo.del', ['id' => $atask['id']], false) }}">
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
              <h5 class="modal-title" id="exampleModalLabel">Edit Config</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('geo.edit', [], false) }}">
              @csrf
              <div class="modal-body">
                <input type="hidden" value="0" name="id" id="edit-id" />
                <div class="form-group row">
                  <label for="edit-name" class="col-sm-4 col-form-label text-sm-right">Name</label>
                  <input type="text" class="form-control col-sm-6" id="edit-name" name="building_name" value="test">
                </div>
                <div class="form-group row">
                  <label for="edit-alat" class="col-sm-4 col-form-label text-sm-right">A Latitude</label>
                  <input type="text" class="form-control col-sm-6" id="edit-alat" name="a_latitude">
                </div>
                <div class="form-group row">
                  <label for="edit-along" class="col-sm-4 col-form-label text-sm-right">A Longitude</label>
                  <input type="text" class="form-control col-sm-6" id="edit-along" name="a_longitude">
                </div>
                <div class="form-group row">
                  <label for="edit-blat" class="col-sm-4 col-form-label text-sm-right">B Latitude</label>
                  <input type="text" class="form-control col-sm-6" id="edit-blat" name="b_latitude">
                </div>
                <div class="form-group row">
                  <label for="edit-blong" class="col-sm-4 col-form-label text-sm-right">B Longitude</label>
                  <input type="text" class="form-control col-sm-6" id="edit-blong" name="b_longitude">
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
    var alat = $(e.relatedTarget).data('alat');
    var along = $(e.relatedTarget).data('along');
    var blat = $(e.relatedTarget).data('blat');
    var blong = $(e.relatedTarget).data('blong');

    //populate the textbox
    $(e.currentTarget).find('input[name="id"]').val(id);
    $(e.currentTarget).find('input[name="building_name"]').val(name);
    $(e.currentTarget).find('input[name="a_latitude"]').val(alat);
    $(e.currentTarget).find('input[name="a_longitude"]').val(along);
    $(e.currentTarget).find('input[name="b_latitude"]').val(blat);
    $(e.currentTarget).find('input[name="b_longitude"]').val(blong);
});

$('a[data-toggle="tooltip"]').tooltip({

    html: true
});
</script>
@stop
