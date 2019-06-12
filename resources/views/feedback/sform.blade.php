@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Feedback Form</div>
                @if(isset($alert))
                <div class="alert alert-success" role="alert">Thanks for the feedback :)</div>
                @endif
                <div class="card-body">
                  <form method="POST" action="{{ route('feedback.submit', [], false) }}">
                    @csrf
                    <h5 class="card-title">Got anything to say to us? :D</h5>
                    <div class="form-group row">
                        <label for="title" class="col-md-4 col-form-label text-md-right">Title</label>
                        <div class="col-md-6">
                            <input id="title" class="form-control" type="text" name="title" maxlength="50" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="content" class="col-md-4 col-form-label text-md-right">Content</label>
                        <div class="col-md-6">
                          <textarea rows="5" class="form-control" id="content" name="content" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Submit Feedback</button>
                        </div>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
