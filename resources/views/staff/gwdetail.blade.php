@extends('layouts.app')

@section('page-css')
<link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">Activity Summary for month {{ $damon }}</div>
                <div class="card-body">
                  @if (session()->has('alert'))
                  <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ session()->get('alert') }}</strong>
                  </div>
                  @endif
                  <form method="GET" action="{{ route('staff.list', [], false) }}">
                    <!-- @csrf -->
                    <input type="hidden" name="staff_id" value="{{ $staffid }}" >
                    <div class="form-group row">
                        <label for="actdate" class="col-md-4 col-form-label text-md-right">Month to display</label>
                        <div class="col-md-4">
                          <input type="date" class="form-control" name="actdate" id="actdate" value="{{ $curdate }}"/>
                        </div>
                        <div class="col-md-2">
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                  </form>
                  <br />
                  {!! $chart->render() !!}
                </div>
              </div>
              <br />
              <div class="card">
                <div class="card-header">List of Activities</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="taskdetailtable" class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Date</th>
                          <th scope="col">Tag</th>
                          <th scope="col">Type</th>
                          <th scope="col">ID / Name</th>
                          <th scope="col">Details</th>
                          <th scope="col">Hours</th>
                          @if($isvisitor == false)
                          <th scope="col">Action</th>
                          @endif
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($activities as $acts)
                        <tr>
                          <td>{{ $acts->activity_date }}</td>
                          <td>{{ $acts->ActCat->descr }}</td>
                          <td>{{ $acts->ActType->descr }}</td>
                          <td>{{ $acts->parent_number }}</td>
                          <td>{{ $acts->details }}</td>
                          <td>{{ $acts->hours_spent }}</td>
                          @if($isvisitor == false)
                          <td>
                            @if($acts->isleave)
                            &nbsp;
                            @else
                            <form action="{{ route('staff.delact', [], false)}}"
                              method="post">
                              <input type="hidden" name="actid" value="{{ $acts->id }}" />
                              @csrf
                              <button type="button" class="btn btn-sm btn-warning" title="Edit" data-toggle="modal" data-target="#editCfgModal"
                              data-id="{{ $acts->id }}"><i class="fa fa-pencil"></i></button>
                              <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
                            </form>
                            @endif
                          </td>
                          @endif
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editCfgModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Amend Diary Entry</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('staff.editact', [], false) }}">
              @csrf
              <div class="modal-body">
                <input type="hidden" value="0" name="id" id="edit-id" />
                <div class="form-group row">
                  <label for="edit-name" class="col-sm-4 col-form-label text-sm-right">Activity Tag</label>
                  <input type="text" class="form-control col-sm-6" id="edit-at"  disabled>
                </div>
                <div class="form-group row">
                  <label for="edit-name" class="col-sm-4 col-form-label text-sm-right">ID / Name</label>
                  <input type="text" class="form-control col-sm-6" id="edit-idn"  disabled>
                </div>
                <div class="form-group row">
                  <label for="edit-name" class="col-sm-4 col-form-label text-sm-right">Activity Category</label>
                  <input type="text" class="form-control col-sm-6" id="edit-ac"  disabled>
                </div>
                <div class="form-group row">
                  <label for="edit-name" class="col-sm-4 col-form-label text-sm-right">Details</label>
                  <textarea rows="3" class="form-control col-sm-6" id="edit-remark" name="details" placeholder="Anything you wish to elaborate regarding this activity" required></textarea>
                </div>
                <div class="form-group row">
                  <label for="edit-name" class="col-sm-4 col-form-label text-sm-right">Hours Spent</label>
                  <input type="number" class="form-control col-sm-3" name="hours" value="1" min="0" max="24" step="0.01" id="edit-hours" />
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js "></script>

<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script type="text/javascript">

$('#editCfgModal').on('show.bs.modal', function(e) {

    //get data-id attribute of the clicked element
    var id = $(e.relatedTarget).data('id');

    var baseeurl='{{ route("staff.act.info") }}';

    $.ajax({
      url: baseeurl + "?actid=" + id ,
      type: "GET",
      success: function(resp) {
        // alert(resp);
        document.getElementById("edit-id").value = resp.id;
        document.getElementById("edit-at").value = resp.at;
        document.getElementById("edit-idn").value = resp.idn;
        document.getElementById("edit-ac").value = resp.ac;
        document.getElementById("edit-remark").value = resp.remark;
        document.getElementById("edit-hours").value = resp.hours;
      },
      error: function(err) {
        $('#editCfgModal').modal('hide');
        alert(err);
      }
    });

});

$(document).ready(function() {
    $('#taskdetailtable').DataTable({
        paging: true,
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel'
        ]
    });
} );
</script>
@stop
