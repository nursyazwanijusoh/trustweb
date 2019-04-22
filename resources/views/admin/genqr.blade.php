@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Generate QR</div>
                <div class="card-body">
                  <form method="post" action="{{ route('admin.genqrp', [], false) }}">
                    @csrf
                    <!-- <h5 class="card-title">Add new task type</h5> -->
                    <div class="form-group row">
                        <label for="qrc" class="col-md-4 col-form-label text-md-right">QR Content</label>
                        <div class="col-md-6">
                            <input id="qrc" class="form-control" type="text" name="qrc" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="qrl" class="col-md-4 col-form-label text-md-right">Label</label>
                        <div class="col-md-6">
                          <input id="qrl" class="form-control" type="text" name="qrl" >
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Generate</button>
                        </div>
                    </div>
                  </form>
                </div>
                <div class="card-header"> </div>
                <div class="card-body">
                  <div class="d-flex flex-wrap">
                    <div class="visible-print border text-center">
                      <b>Scan to check in</b><br />
                      {!! QrCode::size(300)->margin(1)->generate('trUSt : ' . $qrcontent); !!}
                      <p>{{ $qrlabel }}</p>
                      <p style="line-height: 1em">For more info, visit<br />
                        <a href="https://trust.tm.com.my/info">https://trust.tm.com.my/info</a>
                      </p>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
