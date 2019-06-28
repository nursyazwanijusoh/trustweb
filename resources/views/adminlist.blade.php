@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">List of Admins</div>
                <div class="card-body">
                  <div class="card-columns">
                    @foreach($admins as $asub)
                    <div class="card text-white bg-dark">
                      <div class="card-header text-center">{{ $asub['name'] }}</div>
                      <div class="card-body">
                        <p class="card-text">{{ $asub['unit'] }}<br />
                        Email: <a href="mailto:{{ $asub['email'] }}">{{ $asub['email'] }}</a><br />
                        Mobile: {{ $asub['mobile_no'] }}</p>
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
