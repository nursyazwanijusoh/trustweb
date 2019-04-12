@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Work Place Management</div>
                <div class="card-body">
                  <form method="POST" action="{{ route('admin.addbuild', [], false) }}">
                    @csrf
                    <h5 class="card-title">Add new location</h5>
                    <div class="form-group row">
                        <label for="building_name" class="col-md-4 col-form-label text-md-right">Building Name</label>
                        <div class="col-md-6">
                            <input id="building_name" class="form-control" type="text" name="building_name" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="floor_name" class="col-md-4 col-form-label text-md-right">Floor name</label>
                        <div class="col-md-6">
                            <input id="floor_name" class="form-control" type="text" name="floor_name" required>
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
                <!-- <div class="card-header"> </div> -->
                <div class="card-body">
                  <h5 class="card-title">List of work space</h5>
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Building</th>
                        <th scope="col">Floor</th>
                        <th scope="col">Remark</th>
                        <th scope="col">Added By</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($buildlist as $atask)
                      <tr>
                        <td>{{ $atask['building_name'] }}</td>
                        <td>{{ $atask['floor_name'] }}</td>
                        <td>{{ $atask['remark'] }}</td>
                        <td>{{ $atask['created_by'] }}</td>
                        <td><a href="{{ route('admin.delbuild', ['build_id' => $atask['id']]) }}">Remove</a></td>
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
