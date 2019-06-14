@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Partner / Vendor Management</div>
                <div class="card-body">
                  <form method="POST" action="{{ route('partner.add', [], false) }}">
                    @csrf
                    <h5 class="card-title">Add new Partner</h5>
                    <div class="form-group row">
                        <label for="descr" class="col-md-4 col-form-label text-md-right">Company Name</label>
                        <div class="col-md-6">
                            <input id="descr" class="form-control" type="text" name="name" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Add Partner</button>
                        </div>
                    </div>
                  </form>
                </div>
                <div class="card-header"> </div>
                <div class="card-body">
                  <h5 class="card-title">List of partners</h5>
                  <p>
                    Note: removing partner also removes all staff accounts under it
                  </p>
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Company Name</th>
                        <th scope="col">Registered Staff Count</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($currtasklist as $atask)
                      <tr>
                        <td>{{ $atask['comp_name'] }}</td>
                        <td>{{ $atask['staff_count'] }}</td>
                        <td><a href="{{ route('partner.del', ['id' => $atask['id']], false) }}">Remove</a></td>
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
