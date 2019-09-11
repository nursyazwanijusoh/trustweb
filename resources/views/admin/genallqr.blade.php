@extends('layouts.app')

@section('content')

<div class="container-fluid">
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
                    <div class="visible-print border text-center" >
                      <b>Scan to check in</b><br />
                      {!! QrCode::size($width)->margin(1)->generate('trUSt : ' . $seat['qr_code']); !!}
                      <p><u>{{ $seat['label'] }}</u></p>
                      <p style="line-height: 1em">For more info, visit<br />
                        <a href="https://trust.tm.com.my/info">https://trust.tm.com.my/info</a>
                      </p>
                    </div>
                    @endforeach
                  </div>
                </div>
            </div>
        <!-- </div> -->
    </div>
</div>
@endsection
