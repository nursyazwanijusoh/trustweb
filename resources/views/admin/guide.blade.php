@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-3">
                <div class="card-header">Add new guide</div>
                <div class="card-body">
                  <form method="POST" action="{{ route('admin.addguide', [], false) }}">
                    @csrf
                    <div class="form-group row">
                        <label for="descr" class="col-md-4 col-form-label text-md-right">Title</label>
                        <div class="col-md-6">
                            <input id="descr" class="form-control" type="text" name="title" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="remark" class="col-md-4 col-form-label text-md-right">Description</label>
                        <div class="col-md-6">
                          <textarea rows="3" class="form-control" id="remark" name="desc"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="url" class="col-md-4 col-form-label text-md-right">URL</label>
                        <div class="col-md-6">
                            <input id="url" class="form-control" type="text" name="url" required>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="card">
                <div class="card-header">List of guides</div>
                <div class="card-body">
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">URL</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($guides as $atask)
                      <tr>
                        <td>{{ $atask->title }}</td>
                        <td>{{ $atask->desc }}</td>
                        <td>{{ $atask->url }}</td>
                        <td>
                          <form method="post" action="{{ route('admin.delguide') }}">
                            @csrf
                            <input type="hidden" name="gid" value="{{ $atask->id }}" />
                            <button class="btn btn-danger" type="submit" title="Delete" onclick="return confirm('Confirm delete?')"><i class="fa fa-trash"></i></button>
                          </form>
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
