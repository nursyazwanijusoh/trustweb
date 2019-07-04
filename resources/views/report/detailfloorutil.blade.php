@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
              <div class="card-header">Detailed Floor Utilization</div>
              @if(isset($alert))
              <div class="alert alert-danger" role="alert">{{ $alert }}</div>
              @endif
              <div class="card-body">
                <form method="POST" action="{{ route('reports.fudr', [], false) }}">
                  @csrf
                  <!-- <h5 class="card-title">Date range</h5> -->
                  <div class="form-group row">
                      <label for="fdate" class="col-md-4 col-form-label text-md-right">From Date</label>
                      <div class="col-md-6">
                        <input type="date" name="fdate" id="fdate" value="{{ $fdate }}"/>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="tdate" class="col-md-4 col-form-label text-md-right">To Date</label>
                      <div class="col-md-6">
                        <input type="date" name="tdate" id="tdate" value="{{ $tdate }}"/>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label for="lob" class="col-md-4 col-form-label text-md-right">Office Space</label>
                    <div class="col-md-6">
                      <select class="form-control" id="lob" name="floor_id">
                        @foreach ($floors as $atask)
                        <option value="{{ $atask->id }}" >{{ $atask->floor_name . ' @ ' . $atask->building_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                      <div class="col-md-6 offset-md-4">
                          <button type="submit" class="btn btn-primary">Get Detail</button>
                      </div>
                  </div>
                </form>
              </div>
              @if($gotdata)
              <div class="card-body">
                <!-- <h5 class="card-title">Floor Utilization for {{ $chosenn }}</h5> -->
                {!! $chart->render() !!}
              </div>
              @endif

            </div>
        </div>
    </div>
</div>
@endsection
