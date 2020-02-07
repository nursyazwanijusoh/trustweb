@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">Blast Push Notification</div>
                <div class="card-body">
                  <form method="POST" action="{{ route('pn.reg', [], false) }}">
                    @csrf
                    <div class="form-group row">
                        <label for="descr" class="col-md-4 col-form-label text-md-right">Title</label>
                        <div class="col-md-6">
                            <input id="descr" class="form-control" type="text" maxlength="200" name="title" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="remark" class="col-md-4 col-form-label text-md-right">Notification Content</label>
                        <div class="col-md-6">
                          <textarea rows="3" maxlength="1900" class="form-control" id="remark" name="body"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Target User</label>
                        <div class="col-md-6">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="isglobal" onclick="rbselected()" id="isglubal" value="true" checked>
                            <label class="col-form-label" for="isglubal">Global</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="isglobal" onclick="rbselected()" id="isnotglubal" value="false">
                            <label class="col-form-label" for="isnotglubal">Specific Group</label>
                          </div>
                        </div>
                    </div>
                    <div id="grpselector" class="form-group row d-none">
                        @foreach($group as $abuild)
                        <div class="col-md-4">
                          <label class="checkbox-inline">
                            <input type="checkbox" value="{{ $abuild['id'] }}" name="grplist[]" >
                            {{ $abuild->name }}
                          </label>
                        </div>
                        @endforeach
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col text-center">
                            <button type="submit" class="btn btn-primary">Send Push Notification</button>
                        </div>
                    </div>
                  </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Notification Blast History</div>
                <div class="card-body text-center">
                  <div class="table-responsive">
                  <table id="taskdetailtable" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Date Submitted</th>
                        <th scope="col">Status</th>
                        <th scope="col">Submitted By</th>
                        <th scope="col">Title</th>
                        <th scope="col">Body</th>
                        <th scope="col">Sent Count</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($pnlist as $acts)
                      <tr>
                        <td>{{ $acts->created_at }}</td>
                        <td>{{ $acts->status }}</td>
                        <td>{{ $acts->PushAnn->creator->name }}</td>
                        <td>{{ $acts->PushAnn->title }}</td>
                        <td>{{ $acts->PushAnn->body }}</td>
                        <td>{{ $acts->PushAnn->rec_count }}</td>
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
<script type="text/javascript">

$(document).ready(function() {
  $('#taskdetailtable').DataTable({
      "order": [[ 0, "desc" ]]
    });
} );

function rbselected(){
  if(document.getElementById("isglubal").checked == true){
    document.getElementById("grpselector").className  = "form-group row d-none";
  } else {
    document.getElementById("grpselector").className  = "form-group row";
  }
}


</script>
@endsection
