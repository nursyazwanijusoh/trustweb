@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ $title }}</div>
                <div class="card-body">
                  <div class="row mb-0">
                      <div class="col">
                          @if($type == 'pending')
                          <a href="{{ route('admin.reglist', ['type' => 'active'], false) }}"><button class="btn btn-primary">Active Users</button></a>
                          <button class="btn btn-secondary">Pending Approval</button>
                          <a href="{{ route('admin.reglist', ['type' => 'email'], false) }}"><button class="btn btn-primary">Pending Email Verification</button></a>
                          @elseif($type == 'email')
                          <a href="{{ route('admin.reglist', ['type' => 'active'], false) }}"><button class="btn btn-primary">Active Users</button></a>
                          <a href="{{ route('admin.reglist', ['type' => 'pending'], false) }}"><button class="btn btn-primary">Pending Approval</button></a>
                          <button class="btn btn-secondary">Pending Email Verification</button>
                          @else
                          <button class="btn btn-secondary">Active Users</button>
                          <a href="{{ route('admin.reglist', ['type' => 'pending'], false) }}"><button class="btn btn-primary">Pending Approval</button></a>
                          <a href="{{ route('admin.reglist', ['type' => 'email'], false) }}"><button class="btn btn-primary">Pending Email Verification</button></a>
                          @endif
                      </div>
                  </div>
                  <br />
                  <table id="taskdetailtable" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Company</th>
                        <th scope="col">Staff No</th>
                        <th scope="col">Email</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($users as $atask)
                      <tr>
                        <td>{{ $atask->name }}</td>
                        @if(isset($atask->partner_id))
                        <td>{{ $atask->Partner->comp_name }}</td>
                        @else
                        <td>LDAP: {{ $atask->subunit }}</td>
                        @endif
                        <td>{{ $atask->staff_no }}</td>
                        <td>{{ $atask->email }}</td>
                        @if($type == 'pending')
                        <td>
                          <a href="{{ route('admin.regapprove', ['staff_id' => $atask->id], false) }}">
                            <button type="button" class="btn btn-primary btn-sm" title="Approve application">Approve</button>
                          </a>&nbsp;
                          <button id="btnedit" type="button" class="btn btn-warning btn-sm" title="Reject Application"
                          data-toggle="modal" data-target="#editCfgModal"
                          data-id="{{$atask['id']}}" data-name="{{$atask['name']}}"
                          data-act="Reject"
                          >Reject</button>
                        </td>
                        @elseif($type == 'email')
                        <td>
                          <a href="{{ route('verification.resend', ['staff' => $atask->id], false) }}"><button type="button" class="btn btn-success btn-sm" title="Approve application">Resend</button></a>
                          &nbsp;
                          <button id="btnedit" type="button" class="btn btn-warning btn-sm" title="Reject Application"
                          data-toggle="modal" data-target="#editCfgModal"
                          data-id="{{$atask['id']}}" data-name="{{$atask['name']}}"
                          data-act="Reject"
                          >Reject</button>
                        </td>
                        @else
                        <td><button id="btnedit" type="button" class="btn btn-danger btn-sm" title="Delete User"
                        data-toggle="modal" data-target="#editCfgModal"
                        data-id="{{$atask['id']}}" data-name="{{$atask['name']}}"
                        data-act="Delete"
                        >Delete</button></td>
                        @endif
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editCfgModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Reject Application</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('admin.regreject', [], false) }}">
              @csrf
              <div class="modal-body">
                <input type="hidden" value="0" name="staff_id" id="edit-id" />
                <input type="hidden" value="0" name="act" id="edit-act" />
                <div class="form-group row">
                  <label for="edit-name" class="col-sm-3 col-form-label text-sm-right">Name</label>
                  <input type="text" class="form-control col-sm-7" id="edit-name" name="name">
                </div>
                <div class="form-group row">
                  <label for="edit-seq" class="col-sm-3 col-form-label text-sm-right">Remark</label>
                  <textarea rows="3" class="form-control col-sm-7" id="remark" name="remark" required></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm !</button>
              </div>
            </form>
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

$('#editCfgModal').on('show.bs.modal', function(e) {

    //get data-id attribute of the clicked element
    var id = $(e.relatedTarget).data('id');
    var name = $(e.relatedTarget).data('name');
    var action = $(e.relatedTarget).data('act');

    //populate the textbox
    $(e.currentTarget).find('input[name="staff_id"]').val(id);
    $(e.currentTarget).find('input[name="name"]').val(name);
    $(e.currentTarget).find('input[name="act"]').val(action);
    document.getElementById("exampleModalLabel").innerHTML = action + " Application";
});

$(document).ready(function() {
    $('#taskdetailtable').DataTable();
} );
</script>
@stop
