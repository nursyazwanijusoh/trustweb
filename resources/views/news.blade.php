@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-light">
                <div class="card-header">Recent News</div>
                <div class="card-body">
                  <div class="row">
                    @foreach($news as $asub)
                    <div class="col-xl-6">
                      <div class="card mb-3">
                        <div class="card-header text-center">{{ $asub->title }}</div>
                        <div class="card-body">
                          {!! $asub->content !!}
                        </div>
                        <div class="card-footer">Posted at {{ $asub->created_at }}</div>
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
