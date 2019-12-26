@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Add Diary Entry</div>
                @if (session()->has('alert'))
                <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>{{ session()->get('alert') }}</strong>
                </div>
                @endif
                <div class="card-body">
                  <form method="POST" action="{{ route('staff.doaddact', [], false) }}">
                    @csrf
                    <div class="form-group row">
                        <label for="actdate" class="col-md-4 col-form-label text-md-right">Date</label>
                        <div class="col-md-6">
                          <input type="date" name="actdate" id="actdate" value="{{ $curdate }}" min="{{ $mindate }}" max="{{ $curdate }}"/>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="actcat" class="col-md-4 col-form-label text-md-right">Activity Tag</label>
                      <div class="col-md-6">
                        <select class="form-control" id="actcat" name="actcat" required onchange="setTitleInput()">
                          @foreach ($actcats as $act)
                          <option value="{{ $act['descr'] }}" title="{{ $act->remark }}" >{{ $act['descr'] }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="acttype" class="col-md-4 col-form-label text-md-right">Activity Type</label>
                      <div class="col-md-6">
                        <select class="form-control" id="acttype" name="acttype" required>
                          @foreach ($actlist as $act)
                          <option value="{{ $act['descr'] }}"  title="{{ $act->remark }}" >{{ $act['descr'] }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                        <label id="lbl_title" for="parent_no" class="col-md-4 col-form-label text-md-right">Nom</label>
                        <div class="col-md-6" id="inp_title1">
                            <input id="parent_no" class="form-control" type="text" name="parent_no" placeholder="High level activity info">
                        </div>
                        <div class="col-md-6" id="inp_title2">
                          <select class="form-control" id="pbe_sel" name="pbe_id" required>
                            @foreach ($pbes as $pbe)
                            <option value="{{ $pbe->id }}"  title="{{ $pbe->title }}" >{{ $pbe->title }}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="remark" class="col-md-4 col-form-label text-md-right">Details</label>
                        <div class="col-md-6">
                          <textarea rows="3" class="form-control" id="remark" name="details" placeholder="Anything you wish to elaborate regarding this activity" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="hours" class="col-md-4 col-form-label text-md-right">Hours Spent</label>
                        <!-- <div class="col-md-4">
                          <input type="range" class="custom-range" id="hours"
                          oninput="displaysliderval()" name="hourss" min="0" max="8" step="0.1" value="1"/>
                        </div> -->
                        <div class="col-md-2">
                          <input type="number" class="form-control" name="hours" value="1" min="0" max="24" step="0.01" id="hourisid" onchange="updateSlider()" />
                        </div>
                        <!-- <label for="hours" class="col-md-1 col-form-label text-md-right">1</label> -->
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Add Activity</button>
                        </div>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
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
    document.getElementById("lbl_title").innerHTML = "Title";
    document.getElementById("inp_title1").style.display = "block";
    document.getElementById("inp_title2").style.display = "none";
    document.getElementById("parent_no").disabled = false;
    document.getElementById("pbe_sel").disabled = true;
  }

}

$(document).ready(function() {
  setTitleInput();
});

</script>
@endsection
