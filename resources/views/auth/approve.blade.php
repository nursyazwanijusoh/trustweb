@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Pending Approval') }}</div>
                @if (isset($token))
                    <div class="alert alert-success" role="alert">
                        Email verified.
                    </div>
                @endif
                <div class="card-body">
                    Your account require approval from the <a href="{{ route('adminlist', [], false)}}">admins</a> before it can be used.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
