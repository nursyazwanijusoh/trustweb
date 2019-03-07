@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Amer's Playground</div>
                <div class="card-body">
                  <div class="list-group">
                    <a href="{{ route('staff') }}" class="list-group-item list-group-item-action">Staff Menu</a>
                    <a href="{{ route('admin') }}" class="list-group-item list-group-item-action">Admin Menu</a>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
