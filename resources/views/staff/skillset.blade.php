@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Shared Skillset</div>
                @if(isset($alert))
                <div class="alert alert-success" role="alert">{{ $alert }}</div>
                @endif
                <div class="card-body">
                  <form method="POST" action="{{ route('ps.update', [], false) }}">
                    @csrf
                    <input type="hidden" name="cat" value="Common"  />
                    @foreach($skillcatp as $asp)
                    <h5 class="card-title">{{ $asp['name'] }}</h5>
                    @foreach($asp['skills'] as $ask)
                    <div class="form-group row">
                      <legend class="col-form-label col-sm-3 pt-0 text-sm-right">{{ $ask['name'] }}</legend>
                      <div class="col-sm-9">
                        <input type="hidden" name="skill[{{ $ask['id'] }}][id]" value="{{ $ask['id'] }}" />
                        @for($i = 0; $i <= 5; $i++ )
                        <div class="form-check form-check-inline">
                          @if($i == $ask['current'])
                          <input class="form-check-input" type="radio" name="skill[{{ $ask['id'] }}][star]" id="{{ $ask['id'] }}-{{ $i }}" value="{{ $i }}" checked />
                          @else
                          <input class="form-check-input" type="radio" name="skill[{{ $ask['id']}}][star]" id="{{ $ask['id'] }}-{{ $i }}" value="{{ $i }}" />
                          @endif
                          <label class="form-check-label" for="{{ $ask['id'] }}-{{ $i }}">{{ $i }}</label>
                        </div>
                        @endfor
                      </div>
                    </div>
                    @endforeach
                    @endforeach
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                          <button type="submit" class="btn btn-primary">Update Shared Skillset</button>
                        </div>
                    </div>
                  </form>
                </div>
            </div> <br />
            <div class="card">
                <div class="card-header">Additional Skillset</div>
                <div class="card-body">
                  <form method="POST" action="{{ route('ps.update', [], false) }}">
                    @csrf
                    <input type="hidden" name="cat" value="Additional"  />
                    @foreach($skillcatm as $asp)
                    <h5 class="card-title">{{ $asp['name'] }}</h5>
                    @foreach($asp['skills'] as $ask)
                    <div class="form-group row">
                      <legend class="col-form-label col-sm-3 pt-0 text-sm-right">{{ $ask['name'] }}</legend>
                      <div class="col-sm-9">
                        <input type="hidden" name="skill[{{ $ask['id'] }}][id]" value="{{ $ask['id'] }}" />
                        @for($i = 0; $i <= 5; $i++ )
                        <div class="form-check form-check-inline">
                          @if($i == $ask['current'])
                          <input class="form-check-input" type="radio" name="skill[{{ $ask['id'] }}][star]" id="{{ $ask['id'] }}-{{ $i }}" value="{{ $i }}" checked />
                          @else
                          <input class="form-check-input" type="radio" name="skill[{{ $ask['id']}}][star]" id="{{ $ask['id'] }}-{{ $i }}" value="{{ $i }}" />
                          @endif
                          <label class="form-check-label" for="{{ $ask['id'] }}-{{ $i }}">{{ $i }}</label>
                        </div>
                        @endfor
                      </div>
                    </div>
                    @endforeach
                    @endforeach
                    <div class="form-group row mb-0">
                        <div class="col text-center">
                          <button type="submit" class="btn btn-primary">Update Additional Skillset</button>
                          <a href="{{ route('ps.add', [], false)}}"><button type="button" class="btn btn-primary">Add New Skillset</button></a>
                        </div>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
