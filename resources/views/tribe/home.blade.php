@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <p>
        this is just to get the user before sending the session to tribe.
        <br />
        By right the button will be located from home page
    </p>

    <a href="{{route('tribe.validateToken',[],false)}}" class="btn btn-primary ">Edit</a>
    <br />
    <hr />
    <div class="card ">
    <div class="card-header">
    Access Token
    </div>
        <div class="card-body">
            {{$token??"token not exist"}}
        </div>
    </div>

</div>
@endsection