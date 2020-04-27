@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center no-gutters">
          @if($isvisitor == false)
          <div class="col-lg-5">
            <div class="card m-1">
                <div class="card-header">Add Diary Entry. Start work time: {{ $early->toTimeString() }}</div>
                <div class="card-body">
                  @if (session()->has('alert'))
                  <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ session()->get('alert') }}</strong>
                  </div>
                  @endif
                  <form method="POST" action="{{ route('staff.doaddact', [], false) }}">
                    @csrf
                    <div class="form-group row">
                        <label for="actdate" class="col-md-4 col-form-label text-md-right">Date</label>
                        <div class="col-md-6">
                          <input type="date" class="form-control" name="actdate" id="actdate" value="{{ old('actdate', $recdate) }}" min="2020-01-01" max="{{ $curdate }}" onchange="loadActListForDate()"/>
                        </div>
                    </div>
                    @if($isearly == false)
                    <div class="form-group row">
                      <label for="actcat" class="col-md-4 col-form-label text-md-right">Activity Tag</label>
                      <div class="col-md-8">
                        <select class="form-control" id="actcat" name="actcat" required onchange="setTitleInput()">
                          @foreach ($actcats as $act)
                          <option value="{{ $act['descr'] }}" title="{{ $act->remark }}" {{ old('actcat') == $act['descr'] ? 'selected' : '' }} >{{ $act['descr'] }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                        <label id="lbl_title" for="parent_no" class="col-md-4 col-form-label text-md-right">Nom</label>
                        <div class="col-md-8" id="inp_title1">
                            <input id="parent_no" class="form-control" type="text" name="parent_no" value="{{old('parent_no')}}" placeholder="High level activity info" required>
                        </div>
                        <div class="col-md-8" id="inp_title2">
                          <select class="form-control" id="pbe_sel" name="pbe_id" required>
                            @foreach ($pbes as $pbe)
                            <option value="{{ $pbe->id }}"  title="{{ $pbe->title }}" {{ old('pbe_id') == $pbe->id ? 'selected' : '' }} >{{ $pbe->title }}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="acttype" class="col-md-4 col-form-label text-md-right">Activity Type</label>
                      <div class="col-md-8">
                        <select class="form-control" id="acttype" name="acttype" required>
                          @foreach ($actlist as $act)
                          <option value="{{ $act['descr'] }}"  title="{{ $act->remark }}" {{ old('acttype') == $act['descr'] ? 'selected' : '' }} >{{ $act['descr'] }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                        <label for="hours" class="col-md-4 col-form-label text-md-right" title="Spent, not planned">Hours <b>Spent</b></label>
                        <!-- <div class="col-md-4">
                          <input type="range" class="custom-range" id="hours"
                          oninput="displaysliderval()" name="hourss" min="0" max="8" step="0.1" value="1"/>
                        </div> -->
                        <div class="col-md-4">
                          <input type="number" class="form-control{{ $errors->has('hours') ? ' is-invalid' : '' }}" name="hours" value="{{ old('hours', 1) }}" min="0" max="24" step="0.01" id="hourisid" onchange="updateSlider()" />
                          @if ($errors->has('hours'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('hours') }}</strong>
                              </span>
                          @endif
                        </div>
                        <!-- <label for="hours" class="col-md-1 col-form-label text-md-right">1</label> -->
                    </div>
                    <div class="form-group row">
                        <label for="remark" class="col-md-3 col-form-label text-md-right">Details</label>
                        <div class="col-md-9">
                          <textarea rows="3" class="form-control" id="remark" name="details" placeholder="Specific detail regarding this activity" required>{{ old('details') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-0 justify-content-center">
                            <button type="submit" class="btn btn-primary m-1">Add Activity</button>
                            <a href="{{ route('staff', [], false) }}"><button type="button" class="btn btn-success m-1" title="Being unable to navigate on one's own is never a sin">Back to Dashboard</button></a>
                    </div>
                    @else
                    <div class="form-group row mb-0 justify-content-center">
                      <a href="{{ route('clock.list', [], false) }}"><button type="button" class="btn btn-dark m-1" title="I want to start working now">Check in to start working early</button></a>
                    </div>
                    @endif
                  </form>
                </div>
            </div>
          </div>
          <div class="col-lg-7">
            @else
          <div class="col-lg-12">
            @endif
            <div class="card m-1">
              <div class="card-header">Entry for {{ $recdate }}. Total hours: {{ $dfobj->actual_hours }}</div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="taskdetailtable" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Date Entered</th>
                        <th scope="col">Type</th>
                        <th scope="col">ID / Title</th>
                        <th scope="col">Details</th>
                        <th scope="col">Hours</th>
                        @if($isvisitor == false)
                        <th scope="col">Action</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($todayacts as $acts)
                      <tr>
                        <td>{{ $acts->created_at }}</td>
                        @if($acts->isleave)
                        <td>On Leave</td>
                        <td>{{ $acts->parent_number }}</td>
                        <td>{{ $acts->leave_remark }}</td>
                        @else
                        <td>{{ $acts->ActType->descr }}</td>
                        <td>{{ $acts->parent_number }}</td>
                        <td>{{ $acts->details }}</td>
                        @endif
                        <td>{{ $acts->hours_spent }}</td>
                        @if($isvisitor == false)
                        <td class="text-center">
                          @if($acts->isleave)
                          &nbsp;
                          @else
                          <form action="{{ route('staff.delact', [], false)}}"
                            method="post">
                            <input type="hidden" name="actid" value="{{ $acts->id }}" />
                            @csrf
                            <button type="button" class="btn btn-sm btn-warning" title="Edit" data-toggle="modal" data-target="#editCfgModal"
                            data-id="{{ $acts->id }}"><i class="fa fa-pencil"></i></button>
                            <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Confirm delete?')"><i class="fa fa-trash"></i></button>
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
<script>

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

// document.getElementById('actdate').value = new Date().toDateInputValue();
function displaysliderval() {
  var slider = document.getElementById("hours");
  var output = document.getElementById("hourisid");
  output.value = slider.value;
}

function updateSlider() {
  var slider = document.getElementById("hours");
  var output = document.getElementById("hourisid");
  slider.value = output.value;
}

function setTitleInput() {
  var tag_ref = <?php echo $tagref; ?>;
  var e = document.getElementById("actcat");
  var tagstr = e.options[e.selectedIndex].value;

  // get the is_pbe value for this tag
  if(tag_ref.includes(tagstr)){
    document.getElementById("lbl_title").innerHTML = "Project / BE";
    document.getElementById("inp_title1").style.display = "none";
    document.getElementById("inp_title2").style.display = "block";
    document.getElementById("parent_no").disabled = true;
    document.getElementById("pbe_sel").disabled = false;
  } else {
    document.getElementById("lbl_title").innerHTML = "ID / Title";
    document.getElementById("inp_title1").style.display = "block";
    document.getElementById("inp_title2").style.display = "none";
    document.getElementById("parent_no").disabled = false;
    document.getElementById("pbe_sel").disabled = true;
  }

}

function loadActListForDate(){

  var indate = document.getElementById("actdate").value;
  var baseeurl='{{ route("staff.act.dayinfo") }}';

  window.location = baseeurl + "?indate=" + indate;
}

$(document).ready(function() {
  setTitleInput();
});

</script>
@endsection
