@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Work-hours summary for {{ $rlabel }}</div>
                <div class="card-body">
                  <h5 class="card-title">Total Expected Hours: {{ $expthours }}</h5>
                  <table class="table table-striped table-hover table-responsive table-bordered">
                    <thead>
                      <tr>
                        @foreach($header as $ah)
                        @if($ah['isweekend'] == 'y')
                        <td style="white-space:nowrap;"><b>{{ $ah['date'] }}</b></td>
                        @else
                        <td>{{ $ah['date'] }}</td>
                        @endif
                        @endforeach
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($staffs as $acts)
                      <tr>
                        @foreach($acts as $mds)
                        <td style="white-space:nowrap;">{{ $mds }}</td>
                        @endforeach
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
