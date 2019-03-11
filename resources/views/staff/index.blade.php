@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
              <div class="card-header">Staff Home Page</div>
              <div class="card-body">
                <h5 class="card-title">Summary</h5>
                <div class="list-group">
                  <li class="list-group-item list-group-item-info">{{ $opentask }} Open Task</li>
                  <li class="list-group-item list-group-item-success">{{ $donetask }} Completed Task</li>
                </div>
              </div>
              <div class="card-body">
                <h5 class="card-title">Action</h5>
                <div class="list-group">
                  <a href="{{ route('staff.t', ['staff_id' => $staff_id], false) }}" class="list-group-item list-group-item-action">Task Management</a>
                  <a href="{{ route('staff.addact', [], false) }}" class="list-group-item list-group-item-action">Update Daily Activity</a>
                </div>
              </div>
              <div class="card-body">
                <h5 class="card-title">My Subordinate</h5>
                <div class="list-group">
                  <a href="{{ route('admin.build') }}" class="list-group-item list-group-item-action">Building List</a>
                  <a href="{{ route('admin.sr') }}" class="list-group-item list-group-item-action">Bulk Staff Update</a>
                  <a href="#" class="list-group-item list-group-item-action">Edit Staff</a>
                  <a href="{{ route('admin.tt') }}" class="list-group-item list-group-item-action">Task Type List</a>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
