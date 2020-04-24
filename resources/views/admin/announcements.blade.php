@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-3">
                <div class="card-header">Add Announcement</div>
                <div class="card-body">
                  @if (session()->has('alert'))
                  <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ session()->get('alert') }}</strong>
                  </div>
                  @endif
                  <form method="POST" action="{{ route('admin.annc.add', [], false) }}">
                    @csrf
                    <div class="form-group row">
                        <label for="content" class="col-md-4 col-form-label text-md-right">Content</label>
                        <div class="col-md-6">
                            <input id="content" class="form-control" type="text" name="content" required autofocus />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="startdate" class="col-md-4 col-form-label text-md-right">Start Date</label>
                        <div class="col-md-6">
                            <input id="startdate" class="form-control" type="date" name="startdate" value="{{ $today }}" required />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="enddate" class="col-md-4 col-form-label text-md-right">End Date</label>
                        <div class="col-md-6">
                            <input id="enddate" class="form-control" type="date" name="enddate" value="{{ $today }}" required />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="linktext" class="col-md-4 col-form-label text-md-right">Link Text</label>
                        <div class="col-md-6">
                            <input id="linktext" class="form-control" type="text" name="linktext" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="url" class="col-md-4 col-form-label text-md-right">URL</label>
                        <div class="col-md-6">
                            <input id="url" class="form-control" type="text" name="url" />
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                      <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Add</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
          </div>
          <div class="col-md-12">
              <div class="card">
                <div class="card-header">Announcements List</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="fblist" class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Start Date</th>
                          <th scope="col">End Date</th>
                          <th scope="col">Content</th>
                          <th scope="col">Link text</th>
                          <th scope="col">URL</th>
                          <th scope="col">Added By</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($list as $atask)
                        <tr>
                          <td>{{ $atask->start_date }}</td>
                          <td>{{ $atask->end_date }}</td>
                          <td>{{ $atask->content }}</td>
                          <td>{{ $atask->url_text }}</td>
                          <td>{{ $atask->url }}</td>
                          <td>{{ $atask->Creator->name }}</td>
                          <td>
                            <a href="{{ route('admin.annc.del', ['id' => $atask['id']], false) }}">
                              <button type="button" class="btn btn-danger btn-sm" title="Delete">Delete</button>
                            </a>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('page-js')
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" defer>
$(document).ready(function() {
    $('#fblist').DataTable();
} );
</script>
@stop
