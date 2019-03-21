@extends('layouts.app')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Registered User By Division</div>
                <div class="card-body">
                  {!! $chart->container() !!}
                </div>
            </div>
        </div>
    </div>
</div>
{!! $chart->script() !!}
@endsection
