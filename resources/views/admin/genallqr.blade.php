@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <!-- <div class="col-md-10"> -->
            <div class="card">
                <div class="card-header">{{ $location }}</div>
                <div class="card-body">
                  <form method="get" action="{{ route('admin.getallqr', [], false)}}">
                    <input type="hidden" name="build_id" value="{{ $build_id }}"  />
                    <div class="form-group row">
                        <label for="width" class="col-md-4 col-form-label text-md-right">Size (px)</label>
                        <div class="col-md-6">
                            <input id="width" value="{{ $width }}" class="" type="number" name="width" required>
                            <button type="submit" class="btn btn-primary">Regen</button>
                        </div>
                    </div>
                  </form>
                  <div class="d-flex flex-wrap">
                    @foreach($seats as $seat)
                    <div class="visible-print border text-center">
                      {!! QrCode::size($width)->generate('trUSt : ' . $seat['qr_code']); !!}
                      <p>{{ $seat['label'] }}</p>
                    </div>
                    @endforeach
                  </div>
                </div>
            </div>
        <!-- </div> -->
    </div>
</div>
@endsection
