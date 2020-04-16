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
      <div class="col-12">
        <div class="card bg-light mb-3">
          <div class="card-header">{{ $mco->requestor->name }} : Check-ins for {{ $mco->request_date }}</div>
          <div class="card-body">
            <ul class="timeline">
              @foreach($cekins as $psh)
              <li class="timeline-item rounded ml-3 p-3 shadow tlbg">
                  <div class="timeline-arrow"></div>
                  <h2 class="h5 mb-0">{!! $psh->action !!}. <a href="https://www.google.com/maps/search/?api=1&query={{ $psh->latitude . ',' . $psh->longitude }}" target="_blank" class="text-success">See in map</a></h2>
                  <span class="small text-secondary"><i class="fa fa-clock-o mr-1"></i>{{ $psh->created_at }}</span>
                  @if(isset($psh->note))
                  <p class="text-small my-1 font-weight-light">{{ $psh->note }}</p>
                  @endif
                  @if(isset($psh->address))
                  <p class="text-small my-1  font-weight-light">Location: {{ $psh->address }}</p>
                  @endif
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
