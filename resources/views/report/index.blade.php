@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Report Menu</div>
                <div class="card-body">
                  <div class="list-group">
                    <a href="{{ route('reports.regstat', [], false) }}" class="list-group-item list-group-item-action">Registered User Statistic</a>
                    <a href="{{ route('reports.workhour', [], false) }}" class="list-group-item list-group-item-action">Work Hour?</a>
                    <a href="{{ route('reports.depts', [], false) }}" class="list-group-item list-group-item-action">List of Departments?</a>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
