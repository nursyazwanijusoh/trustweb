@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Admin Menu</div>
                <div class="card-body">
                  <h5 class="card-title">User Management</h5>
                  <div class="list-group">
                    <!-- <a href="{{ route('admin.sr', [], false) }}" class="list-group-item list-group-item-action">Assign Floor Access By Division</a> -->
                    <a href="{{ route('admin.list', [], false) }}" class="list-group-item list-group-item-action">Admin List</a>
                    <a href="{{ route('admin.st', [], false) }}" class="list-group-item list-group-item-action">Edit Staff (TM)</a>
                    <a href="{{ route('admin.loadji', [], false) }}" class="list-group-item list-group-item-action">Load TM Staff profile (JI from SAP?)</a>
                    <a href="{{ route('admin.reglist', [], false) }}" class="list-group-item list-group-item-action">Vendor Users
                    @if($prc > 0)
                    <span class="badge badge-warning">{{ $prc }}</span>
                    @endif
                    </a>
                    <a href="{{ route('feedback.list', [], false) }}" class="list-group-item list-group-item-action">Feedbacks
                    @if($fbc > 0)
                    <span class="badge badge-info">{{ $fbc }}</span>
                    @endif
                    </a>
                  </div>
                  <br />
                  <h5 class="card-title">Infrastructure Management</h5>
                  <div class="list-group">
                    <a href="{{ route('geo.list', [], false) }}" class="list-group-item list-group-item-action">Office Building List</a>
                    <a href="{{ route('admin.build', [], false) }}" class="list-group-item list-group-item-action">Floor List</a>
                    <a href="{{ route('admin.meetroom', [], false) }}" class="list-group-item list-group-item-action">Meeting Rooms</a>
                    <a href="{{ route('admin.genqrg', [], false) }}" class="list-group-item list-group-item-action">Generate Custom QR</a>
                  </div>
                  <br />
                  <h5 class="card-title">LOV Management</h5>
                  <div class="list-group">
                    <a href="{{ route('admin.at', [], false) }}" class="list-group-item list-group-item-action">Activity Type List</a>
                    <a href="{{ route('admin.tt', [], false) }}" class="list-group-item list-group-item-action">Activity Category List</a>
                    <a href="{{ route('sc.list', [], false) }}" class="list-group-item list-group-item-action">Skill Category</a>
                    <a href="{{ route('ss.list', [], false) }}" class="list-group-item list-group-item-action">Shared Skillset List</a>
                    <a href="{{ route('partner.list', [], false) }}" class="list-group-item list-group-item-action">Partner / Vendor List</a>
                    <a href="{{ route('cfg.list', [], false) }}" class="list-group-item list-group-item-action">Common Configs</a>
                    <a href="{{ route('admin.lov', [], false) }}" class="list-group-item list-group-item-action">Department LOVs</a>
                    <a href="{{ route('avatar.list', [], false) }}" class="list-group-item list-group-item-action">Avatar List</a>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
