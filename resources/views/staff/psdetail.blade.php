@extends('layouts.app')

@section('page-css')
<style>
ul.timeline {
    list-style-type: none;
    position: relative;
    padding-left: 1.5rem;
}

 /* Timeline vertical line */
ul.timeline:before {
    content: ' ';
    background: #f0f;
    display: inline-block;
    position: absolute;
    left: 16px;
    width: 4px;
    height: 100%;
    z-index: 400;
    border-radius: 1rem;
}

li.timeline-item {
    margin: 20px 0;
}

/* Timeline item arrow */
.timeline-arrow {
    border-top: 0.5rem solid transparent;
    border-right: 0.5rem solid #cffcdb;
    border-bottom: 0.5rem solid transparent;
    display: block;
    position: absolute;
    left: 2rem;
}

/* Timeline item circle marker */
li.timeline-item::before {
    content: ' ';
    background: #ddd;
    display: inline-block;
    position: absolute;
    border-radius: 50%;
    border: 3px solid #f00;
    left: 11px;
    width: 14px;
    height: 14px;
    z-index: 400;
    box-shadow: 0 0 5px rgba(254, 0, 0, 0.2);
}

li.tlbg {
    background: #cffcdb;
    background: -webkit-linear-gradient(to right, #cffcdb, #f5d5ee);
    background: linear-gradient(to right, #cffcdb, #f5d5ee);
}


</style>
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">

      @if($isvisitor == false || $isboss == true)
      <div class="col-12">
        <div class="card mb-3">
            <div class="card-header">Update skill competency</div>
            <div class="card-body">
              @if (session()->has('alert'))
              <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>{{ session()->get('alert') }}</strong>
              </div>
              @endif
              <form method="POST" action="{{ route('ps.mod', [], false) }}">
                @csrf
                <div class="form-group row">
                  <legend class="col-form-label col-md-4 pt-0 text-md-right">Competency Level</legend>
                  <div class="col-md-8">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="rate" id="noob" value="1" {{ $ps->level == 1 ? 'checked' : '' }} />
                      <label class="form-check-label" for="noob">Beginner</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="rate" id="soso" value="2" {{ $ps->level == 2 ? 'checked' : '' }}/>
                      <label class="form-check-label" for="soso">Intermediate</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="rate" id="e1337" value="3" {{ $ps->level == 3 ? 'checked' : '' }} />
                      <label class="form-check-label" for="e1337">Expert</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                    <label for="ddremark" class="col-md-4 col-form-label text-md-right">Remark</label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="remark" id="ddremark" placeholder="Some comment here" maxlength="300" required/>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col text-center">
                      @if($ps->status == 'N' && $isboss == true)
                      <button type="submit" class="btn btn-primary" name="action" value="A" title="Approve with the new competency that you can set above">Approve</button>
                      <button type="submit" class="btn btn-danger" name="action" value="R" title="This will set competency to 0">Delete</button>
                      @else
                      <button type="submit" class="btn btn-primary" name="action" value="C">Update</button>
                      <button type="submit" class="btn btn-warning" name="action" value="D" title="This will set competency to 0">Delete</button>
                      @endif
                    </div>
                </div>
                <input type="hidden" name="psid" value="{{ $ps->id }}"  />
              </form>
            </div>
        </div>
      </div>
      @endif
      <div class="col-12">
        <div class="card bg-light mb-3">
          <div class="card-header">History of skill {{ $ps->CommonSkill->name }} : <a href="{{ route('ps.list', ['staff_id' => $owner->id], false) }}">{{ $owner->name }}</a></div>
          <div class="card-body">
            <ul class="timeline">
              @foreach($ps->Histories as $psh)
              <li class="timeline-item rounded ml-3 p-3 shadow tlbg">
                  <div class="timeline-arrow"></div>
                  <h2 class="h5 mb-0">{!! $psh->GetPreText() !!} <a href="{{ route('staff', ['staff_id' => $psh->ActBy->id], false) }}"  class="text-success">{{ $psh->ActBy->name }}</a> {!! $psh->GetPostText() !!}</h2><span class="small text-secondary"><i class="fa fa-clock-o mr-1"></i>{{ date_format($psh->created_at, 'D, d M, Y')}}</span>
                  <p class="text-small mt-2 font-weight-light">{{ $psh->remark }}</p>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
