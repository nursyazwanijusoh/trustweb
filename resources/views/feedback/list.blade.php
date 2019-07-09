@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        @if($type == 'active')
                        <button class="btn btn-secondary">Open Feedback</button>
                        <a href="{{ route('feedback.list', ['type' => 'closed'], false) }}"><button class="btn btn-primary">Closed Feedback</button></a>
                        @else
                        <a href="{{ route('feedback.list', ['type' => 'active'], false) }}"><button class="btn btn-primary">Open Feedback</button></a>
                        <button class="btn btn-secondary">Closed Feedback</button>
                        @endif
                    </div>
                </div>
                  @if($type == 'active')
                  <h5 class="card-title">List of Open Feedback</h5>
                  @else
                  <h5 class="card-title">List of Closed Feedback</h5>
                  @endif
                  <table id="fblist" class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Title</th>
                        <th scope="col">Content</th>
                        @if($type == 'active')
                        <th scope="col">Created On</th>
                        <th scope="col">Action</th>
                        @else
                        <th scope="col">Close Remark</th>
                        <th scope="col">Closed By</th>
                        <th scope="col">Close Date</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $atask)
                      <tr title="{{ $atask->agent }}">
                        @if($atask->staff_id == 0)
                        <td>{{ $atask->name }}</td>
                        @else
                        <td><a title="{{ $atask->name }}" href="{{ route('staff', ['staff_id' => $atask->staff_id], false) }}">{{ $atask->staff_no }}</a></td>
                        @endif
                        <td>{{ $atask->title }}</td>
                        <td>{{ $atask->content }}</td>
                        @if($type == 'active')
                        <td>{{ $atask->created_at }}</td>
                        <td><a href="{{ route('feedback.close', ['id' => $atask->id], false) }}">Close</a></td>
                        @else
                        <td>{{ $atask->remark }}</td>
                        <td>{{ $atask->Closer->name }}</td>
                        <td>{{ $atask->updated_at }}</td>
                        @endif
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-js')
<!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" defer>
$(document).ready(function() {
    $('#fblist').DataTable();
} );
</script>
@stop
