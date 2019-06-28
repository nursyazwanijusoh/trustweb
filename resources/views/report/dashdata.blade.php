@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Get your data for the dashboard here</div>
                @if(isset($alert))
                <div class="alert alert-warning" role="alert">{{ $alert }}</div>
                @endif
                <div class="card-body">
                  <form method="POST" action="{{ route('dash.fetch', [], false) }}" id="whform">
                    @csrf
                    <h5 class="card-title">Select date range</h5>
                    <div class="form-group row">
                        <label for="fdate" class="col-md-4 col-form-label text-md-right">From</label>
                        <div class="col-md-6">
                          <input type="date" name="fdate" id="fdate" required />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="todate" class="col-md-4 col-form-label text-md-right">To (not inclusive)</label>
                        <div class="col-md-6">
                          <input type="date" name="todate" id="todate" required />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary" name="subtype" value="checkin">Get Check-Ins Data</button>
                            <!-- <button type="submit" class="btn btn-primary" name="subtype" value="gwd">Get GWD Activities Data</button> -->
                        </div>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
