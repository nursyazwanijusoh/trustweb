@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Add Activity</div>
                @if(isset($alert))
                <div class="alert alert-success" role="alert">Activity added</div>
                @endif
                <div class="card-body">
                  <form method="POST" action="{{ route('staff.doaddact', [], false) }}">
                    @csrf
                    <!-- <h5 class="card-title">Add new task type</h5> -->
                    <!-- <div class="form-group row">
                        <label for="lob" class="col-md-4 col-form-label text-md-right">Group</label>
                        <div class="col-md-6">
                            <input id="lob" type="text" name="lob" maxlength="15" required autofocus>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label for="actdate" class="col-md-4 col-form-label text-md-right">Date</label>
                        <div class="col-md-6">
                          <input type="date" name="actdate" id="actdate" value="{{ $curdate }}"/>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="acttask" class="col-md-4 col-form-label text-md-right">Task</label>
                      <div class="col-md-6">
                        <select class="form-control" id="acttask" name="acttask" required>
                          @foreach ($tasklist as $atask)
                          <option value="{{ $atask['id'] }}" {{ $atask['sel'] }} >{{ $atask['name'] }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="acttype" class="col-md-4 col-form-label text-md-right">Activity Type</label>
                      <div class="col-md-6">
                        <select class="form-control" id="acttype" name="acttype" required>
                          @foreach ($actlist as $act)
                          <option value="{{ $act['id'] }}" >{{ $act['descr'] }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                        <label for="remark" class="col-md-4 col-form-label text-md-right">Remark</label>
                        <div class="col-md-6">
                          <textarea rows="3" class="form-control" id="remark" name="remark"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="hours" class="col-md-4 col-form-label text-md-right">Hours Spent</label>
                        <div class="col-md-6">
                          <input type="range" class="custom-range" id="hours"
                          oninput="displaysliderval()" name="hours" min="0" max="8" step="0.1" value="1"/>
                        </div>
                        <label for="hours" class="col-md-1 col-form-label text-md-right" id="hourisid">1</label>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                          @if ($gottask == true)
                            <button type="submit" class="btn btn-primary">Add Activity</button>
                            <a href="{{ route('staff.t', [], false) }}"><button type="button" class="btn btn-success">Back to Task List</button></a>
                          @else
                            <a href="{{ route('staff.t', [], false) }}"><button type="button" class="btn btn-warning">Add Task First</button></a>
                          @endif
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
  output.innerHTML = slider.value;
}
</script>
@endsection
