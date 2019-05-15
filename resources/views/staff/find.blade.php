@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
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
                @if($result != 'empty')

                @if($result == '404')
                <div class="alert alert-error" role="alert">No result</div>
                @else
                <div class="card-header"> </div>
                <div class="card-body">
                  <!-- <h5 class="card-title">List of task type</h5> -->
                  <table class="table table-striped table-hover">
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
                @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
