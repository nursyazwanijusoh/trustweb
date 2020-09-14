@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center no-gutters">
    <div class="col-lg-12">
      <div class="card mb-3">
        <div class="card-header">Check-in By Division (division with no check-in will not be shown)</div>
        <div class="card-body">
          {!! $sumchart->render() !!}
        </div>
      </div>
    </div>


    <div class="col-lg-12">
      <div class="card mb-3 p-1">
        <div class="card-header">Floor Utilization Breakdown</div>
        <div class="card-body">
          <div class="row">
          @foreach($floors as $af)
            <div class="col-lg-6">
              <div class="card mb-3 mx-1">
                <div class="card-body">
                  {!! $af->chart->render() !!}
                </div>
                <div class="card-footer">
                  {{ $af->floor_name }} - {{ $af->building_name }}
                </div>
              </div>
            </div>
          @endforeach
          </div>
        </div>
      </div>
    </div>


  </div>
</div>
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script type="text/javascript">
$(document).ready(function() {

} );

</script>
@stop
