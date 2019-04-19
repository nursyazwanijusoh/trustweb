@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Work-hours detail for {{ $name }}</div>
                <div class="card-body">
                  <h5 class="card-title">Data from {{ $fromdate }} to {{ $todate }}</h5>
                  <table class="table table-striped table-hover table-responsive table-bordered">
                    <thead>
                      <tr>
                        @foreach($header as $ah)
                        <th>{{ $ah }}</th>
                        @endforeach
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $acts)
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
