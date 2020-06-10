@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card bg-light">
                <div class="card-header">Guides</div>
                <div class="card-body">
                  <div class="card-columns">
                    @foreach($guide as $asub)
                    <div class="card">
                      <div class="card-header text-center"><a href="{{ $asub->url }}" target="_blank">{{ $asub->title }}</a></div>
                      <div class="card-body">
                        <p class="card-text">{!! nl2br($asub->desc) !!}</p>
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
