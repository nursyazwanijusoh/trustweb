@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Update user profile</div>
                <div class="card-body">
                  @if(isset($alert))
                  <div class="alert alert-info" role="alert">{{ $alert }}</div>
                  @endif
                  <form method="POST" enctype="multipart/form-data" action="{{ route('admin.doloadji', [], false) }}">
                    @csrf
                    <div class="form-group row">
                        <label for="infile" class="col-md-4 col-form-label text-md-right">Select file</label>
                        <div class="col-md-6">
                            <input id="infile" class="form-control" type="file" name="infile" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Load data</button>
                            <a href="{{ route('admin.dlji', [], false)}}"><button type="button" class="btn btn-info">Download sample</button></a>
                        </div>
                    </div>
                  </form>
                </div>
              </div><br />
              @if(isset($loaded))
              <div class="card">
                <div class="card-header">Summary of loaded data</div>
                <div class="card-body">
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Division</th>
                        <th scope="col">Count</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($dataasummary as $atask)
                      <tr>
                        <td>{{ $atask['div'] }}</td>
                        <td>{{ $atask['count'] }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
