@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Trust Mobile App Download Page</div>
                @if(isset($alert))
                <div class="alert alert-success" role="alert">{{ $alert }}</div>
                @endif
                <div class="card-body">
                  <h5 class="card-title">Download iOS Installer</h5>
                  <div class="form-group row mb-0">
                      <div class="col-md-6 offset-md-4">
                        @if($ipa)
                          <a href="{{ route('app.down', ['type' => 'ipa'], false) }}"><button id="ios_dl" type="button" class="btn btn-primary">Download</button></a>
                        @else
                          <button id="ios_dl" type="button" class="btn btn-secondary" disabled>Not Available</button>
                        @endif
                        @if( Auth::user() !== null && Auth::user()->role == 0 && $ipa)
                        <a href="{{ route('app.del', ['type' => 'ipa'], false) }}"><button type="button" class="btn btn-danger">Delete</button></a>
                        @endif
                      </div>
                  </div>
                  <br />
                  <h5 class="card-title">Download Android Installer</h5>
                  <div class="form-group row mb-0">
                      <div class="col-md-6 offset-md-4">
                        @if($ipk)
                          <a href="{{ route('app.down', ['type' => 'ipk'], false) }}"><button id="and_dl" type="button" class="btn btn-primary">Download</button></a>
                        @else
                          <button id="and_dl"  type="button" class="btn btn-secondary" disabled>Not Available</button>
                        @endif
                        @if( Auth::user() !== null && Auth::user()->role == 0 && $ipk)
                        <a href="{{ route('app.del', ['type' => 'ipk'], false) }}"><button type="button" class="btn btn-danger">Delete</button></a>
                        @endif
                      </div>
                  </div>

                  @if( Auth::user() !== null && Auth::user()->role == 0 )
                  <hr />
                  <h5 class="card-title">Upload new IPA</h5>
                  <form method="POST" action="{{ route('app.up', [], false) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="ipa"  />
                    <div class="input-group mb-3">
                      <div class="custom-file">
                        <input type="file" class="custom-file-label" name="inputfile" required >
                      </div>
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">Upload IPA</button>
                      </div>
                    </div>
                  </form>
                  <hr />
                  <h5 class="card-title">Upload new IPK</h5>
                  <form method="POST" action="{{ route('app.up', [], false) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="ipk"  />
                    <div class="input-group mb-3">
                      <div class="custom-file">
                        <input type="file" class="custom-file-label" name="inputfile" required >
                      </div>
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">Upload IPK</button>
                      </div>
                    </div>
                  </form>
                  @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
