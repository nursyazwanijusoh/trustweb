@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Admin Menu</div>
                <div class="card-body">
                  <div class="list-group">
                    <a href="{{ route('admin.build') }}" class="list-group-item list-group-item-action">Building List</a>
                    <a href="#" class="list-group-item list-group-item-action">Bulk Staff Update</a>
                    <a href="#" class="list-group-item list-group-item-action">Edit Staff</a>
                    <a href="{{ route('admin.tt') }}" class="list-group-item list-group-item-action">Task Type List</a>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
