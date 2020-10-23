@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="card-columns">
      <div class="card text-white bg-secondary mb-2">
        <div class="card-header">User Management</div>
        <div class="card-body">
          <div class="list-group">
            <!-- <a href="{{ route('admin.sr', [], false) }}" class="list-group-item list-group-item-action">Assign Floor Access By Division</a> -->
            <a href="{{ route('admin.list', [], false) }}" class="list-group-item list-group-item-action">Admin List</a>
            <a href="{{ route('admin.st', [], false) }}" class="list-group-item list-group-item-action">User Access Level</a>
            <a href="{{ route('admin.usercompare', [], false) }}" class="list-group-item list-group-item-action">Overwrite User Data</a>
            <a href="{{ route('admin.loadsapform', [], false) }}" class="list-group-item list-group-item-action">Manual trigger SAP data load</a>
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
            <a href="{{ route('pn.form', [], false) }}" class="list-group-item list-group-item-action">Blast Push Notification</a>
            <!-- <a href="{{ route('admin.bulkupdate', [], false) }}" class="list-group-item list-group-item-action">Bulk Upload to Update</a> -->
          </div>
        </div>
      </div>
      <div class="card bg-light mb-2">
        <div class="card-header">Opportunity Config</div>
        <div class="card-body">
          <div class="list-group">
            <a href="{{ route('art.list', [], false) }}" class="list-group-item list-group-item-action">Agile Resource Team</a>
          </div>
        </div>
      </div>
      <div class="card text-white bg-success mb-2">
        <div class="card-header">Diary LOVs</div>
        <div class="card-body">
          <div class="list-group">
            <a href="{{ route('admin.tt', [], false) }}" class="list-group-item list-group-item-action">Activity Category List</a>
            <a href="{{ route('admin.at', [], false) }}" class="list-group-item list-group-item-action">Activity Type List</a>
            <a href="{{ route('cgrp.list', [], false) }}" class="list-group-item list-group-item-action">Division Groups</a>
            <a href="{{ route('leave.list', [], false) }}" class="list-group-item list-group-item-action">Leave Types</a>
            <a href="{{ route('ph.list', [], false) }}" class="list-group-item list-group-item-action">Public Holidays</a>
            <a href="{{ route('admin.fridayhours', [], false) }}" class="list-group-item list-group-item-action">Friday Work Hour</a>
          </div>
        </div>
      </div>
      <div class="card text-white bg-info mb-2">
        <div class="card-header">Skill Competency LOVs</div>
        <div class="card-body">
          <div class="list-group">
            <a href="{{ route('sc.list', [], false) }}" class="list-group-item list-group-item-action">Skill Category</a>
            <a href="{{ route('st.list', [], false) }}" class="list-group-item list-group-item-action">Skill Type</a>
            <a href="{{ route('ss.list', [], false) }}" class="list-group-item list-group-item-action">Shared Skillset List</a>
            <a href="{{ route('bauexp.list', [], false) }}" class="list-group-item list-group-item-action">Experience / Involvement</a>
            <a href="{{ route('bauexp.role.list', [], false) }}" class="list-group-item list-group-item-action">Involvement Roles</a>
          </div>
        </div>
      </div>
      <div class="card bg-warning mb-2">
        <div class="card-header">Misc Admn Menu</div>
        <div class="card-body">
          <div class="list-group">
            <a href="{{ route('admin.annc', [], false) }}" class="list-group-item list-group-item-action">Announcement</a>
            <a href="{{ route('avatar.list', [], false) }}" class="list-group-item list-group-item-action">Avatar List</a>
            <a href="{{ route('cfg.list', [], false) }}" class="list-group-item list-group-item-action">Common Configs</a>
            <a href="{{ route('admin.lov', [], false) }}" class="list-group-item list-group-item-action">Department LOVs</a>
            <a href="{{ route('admin.guides', [], false) }}" class="list-group-item list-group-item-action">Guides</a>
            <a href="{{ route('mco.rpt', [], false) }}" class="list-group-item list-group-item-action">MCO Permit Requests</a>
            <a href="{{ route('admin.news.list', [], false) }}" class="list-group-item list-group-item-action">News</a>
            <a href="{{ route('partner.list', [], false) }}" class="list-group-item list-group-item-action">Partner / Vendor List</a>
          </div>
        </div>
      </div>
      <div class="card text-white bg-dark mb-2">
          <div class="card-header">Infrastructure Management</div>
          <div class="card-body">
          <div class="list-group">
            <a href="{{ route('geo.list', [], false) }}" class="list-group-item list-group-item-action">Office Building List</a>
            <a href="{{ route('admin.build', [], false) }}" class="list-group-item list-group-item-action">Floor List</a>
            <a href="{{ route('admin.meetroom', [], false) }}" class="list-group-item list-group-item-action">Meeting Rooms</a>
            <a href="{{ route('admin.genqrg', [], false) }}" class="list-group-item list-group-item-action">Generate Custom QR</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
