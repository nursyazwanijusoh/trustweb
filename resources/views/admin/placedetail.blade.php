@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Place Details</div>
                <div class="card-body">
                  <form method="POST" action="{{ route('admin.modbuild', [], false) }}">
                    @csrf
                    <h5 class="card-title">Edit Place Information</h5>
                    <input type="hidden" name="build_id" value="{{ $build['id'] }}"  />
                    <div class="form-group row">
                        <label for="building_name" class="col-md-4 col-form-label text-md-right">Office Building</label>
                        <div class="col-md-6">
                          <select class="form-control" id="building_name" name="office_id" required>
                            @foreach ($office as $atask)
                            <option value="{{ $atask['id'] }}" {{ $atask['selected'] }}>{{ $atask['building_name'] }}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="floor_name" class="col-md-4 col-form-label text-md-right">Floor name</label>
                        <div class="col-md-6">
                            <input id="floor_name" value="{{ $build['floor_name'] }}" class="form-control" type="text" name="floor_name" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="unit" class="col-md-4 col-form-label text-md-right">Unit</label>
                        <div class="col-md-6">
                            <input id="unit" value="{{ $build['unit'] }}" class="form-control" type="text" name="unit" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="remark" class="col-md-4 col-form-label text-md-right">Remark</label>
                        <div class="col-md-6">
                          <textarea rows="3" class="form-control" id="remark" name="remark" required>{{ $build['remark'] }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                          @if($build['seat_count'] != 0)
                          <a href="{{ route('admin.delallseat', ['build_id' => $build['id']], false) }}"><button type="button" class="btn btn-danger">Remove all seats</button></a>
                          <a href="{{ route('admin.getallqr', ['build_id' => $build['id']], false) }}"><button type="button" class="btn btn-warning">Get ALL QR Code</button></a>
                          @endif
                          <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                  </form>
                  <form method="POST" action="{{ route('admin.genseats', [], false) }}">
                    @csrf
                    <h5 class="card-title">Add Seats</h5>
                    <input type="hidden" name="build_id" value="{{ $build['id'] }}"  />
                    <div class="form-group row">
                        <label for="curr_count" class="col-md-4 col-form-label text-md-right">Current Seat Count</label>
                        <div class="col-md-6">
                            <input id="curr_count" value="{{ $build['seat_count'] }}" class="form-control" type="number" disabled >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="add_count" class="col-md-4 col-form-label text-md-right">Add How Many?</label>
                        <div class="col-md-6">
                            <input id="add_count" class="form-control" type="number" name="add_count" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="label_pref" class="col-md-4 col-form-label text-md-right">Label Prefix</label>
                        <div class="col-md-6">
                            <input id="label_pref" class="form-control" type="text" name="label_pref" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="label_suf" class="col-md-4 col-form-label text-md-right">Label Suffix</label>
                        <div class="col-md-6">
                            <input id="label_suf" class="form-control" type="text" name="label_suf">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="qr_pref" class="col-md-4 col-form-label text-md-right">QR Prefix</label>
                        <div class="col-md-6">
                          <input id="qr_pref" class="form-control" type="text" name="qr_pref" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="qr_suf" class="col-md-4 col-form-label text-md-right">QR Suffix</label>
                        <div class="col-md-6">
                          <input id="qr_suf" class="form-control" type="text" name="qr_suf">
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Create Seat</button>
                        </div>
                    </div>
                  </form>

                </div>
                <!-- <div class="card-header"> </div> -->
                <div class="card-body">
                  <h5 class="card-title">List of seats</h5>
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Label</th>
                        <th scope="col">Status</th>
                        <th scope="col" colspan="2" >Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($seatlist as $atask)
                      <tr>
                        <td>{{ $atask['label'] }}</td>
                        <td>{{ $status[$atask['status']] }}</td>
                        <td><a href="{{ route('admin.getqr', ['seat_id' => $atask['id']], false) }}">Get QR</a></td>
                        <td><a href="{{ route('admin.delaseat', ['seat_id' => $atask['id']], false) }}">Delete</a></td>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
