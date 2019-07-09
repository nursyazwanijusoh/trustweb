@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Close Feedback</div>
                <div class="card-body">
                  <form method="POST" action="{{ route('feedback.doclose', [], false) }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $feedback->id }}" />
                    <div class="form-group row">
                        <label for="ctc" class="col-md-4 col-form-label text-md-right">Email / Mobile No</label>
                        <div class="col-md-6">
                            <input id="ctc" class="form-control" type="text" readonly value="{{ $contact }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="title" class="col-md-4 col-form-label text-md-right">Title</label>
                        <div class="col-md-6">
                            <input id="title" class="form-control" type="text" readonly value="{{ $feedback->title }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="content" class="col-md-4 col-form-label text-md-right">Content</label>
                        <div class="col-md-6">
                          <textarea rows="3" class="form-control" id="content" readonly>{{ $feedback->content }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="agent" class="col-md-4 col-form-label text-md-right">Device Info</label>
                        <div class="col-md-6">
                          <textarea rows="3" class="form-control" id="agent" readonly>{{ $feedback->agent }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="content" class="col-md-4 col-form-label text-md-right">Remark</label>
                        <div class="col-md-6">
                          <textarea rows="3" class="form-control" id="content" name="remark" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row text-center">
                        <div class="col custom-control custom-checkbox">
                          <input id="sendemail" class="custom-control-input" type="checkbox" name="sendemail" {{ $isemail }} >
                          <label for="sendemail" class="custom-control-label">Send Response Email?</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0 text-center">
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Close Feedback</button>
                        </div>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
