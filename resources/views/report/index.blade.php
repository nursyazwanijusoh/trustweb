@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Report Menu</div>
                <div class="card-body">
                  <!-- <h5 class="card-title">System</h5>
                  <div class="list-group">
                    <a href="{{ route('reports.regstat', [], false) }}" class="list-group-item list-group-item-action">Registered User Statistic</a>
                    <a href="{{ route('reports.divcheckin', [], false) }}" class="list-group-item list-group-item-action">Check-in By Division</a>
                    <a href="{{ route('reports.depts', [], false) }}" class="list-group-item list-group-item-action">List of Departments?</a>
                  </div>
                  <br /> -->

                  <h5 class="card-title">Diary / GWD</h5>
                  <div class="list-group">
                    <a href="{{ route('report.gwd.summary', [], false) }}" class="list-group-item list-group-item-action">Group Summary</a>
                    <a href="{{ route('report.gwd.divsum', [], false) }}" class="list-group-item list-group-item-action">Division Summary</a>
                    <a href="{{ route('report.gwd.detail', [], false) }}" class="list-group-item list-group-item-action">Details by Division</a>
                  </div>

                  @if($role <= 2)
                  <!-- <br />
                  <h5 class="card-title">Hot Desking</h5>
                  <div class="list-group">
                    <a href="{{ route('hdreports.dbdf', [], false) }}" class="list-group-item list-group-item-action">Daily Checkin by Division</a>
                    <a href="{{ route('hdreports.wsu', [], false) }}" class="list-group-item list-group-item-action">Workspace Occupants</a>

                    <a href="{{ route('reports.fud', [], false) }}" class="list-group-item list-group-item-action">Detailed Floor Utilization</a>
                  </div> -->
                  @endif
                  <br />
                  <h5 class="card-title">Misc</h5>
                  <a href="{{ route('reports.floorutil', [], false) }}" class="list-group-item list-group-item-action">Current Floor Utilization</a>
                  <a href="{{ route('staff.find', [], false) }}" class="list-group-item list-group-item-action">Find Staff</a>
                  <a href="{{ route('dash.index', [], false) }}" class="list-group-item list-group-item-action">Dashboard Data Fetch</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
