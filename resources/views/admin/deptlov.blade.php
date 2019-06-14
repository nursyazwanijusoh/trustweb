@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Scan LDAP for list of departments</div>
                @if(isset($err))
                <div class="alert alert-warning" role="alert">{{ $err }}</div>
                @endif
                <div class="card-body">
                  <form method="POST" action="{{ route('admin.reflov', [], false) }}">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Refresh LOVs (will take a while)</button>
                        </div>
                    </div>
                  </form>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Unit</h5>
                  <div class="card-columns">
                    @foreach($units as $asub)
                    <div class="card text-white bg-dark">
                      <div class="card-header text-center">{{ $asub['pporgunitdesc'] }}</div>
                      <div class="card-body">
                        <p class="card-text">
                          <ul>
                          @foreach($asub['subunit'] as $subu)
                          <li>{{ $subu['ppsuborgunitdesc'] }}</li>
                          @endforeach
                        </ul>
                        </p>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
