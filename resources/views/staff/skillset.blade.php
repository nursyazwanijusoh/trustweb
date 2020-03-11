@extends('layouts.app')

@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if($isvisitor == false || $isboss == true)
            <div class="card mb-3">
                <div class="card-header">Add Skill That No One Probably Care</div>
                <div class="card-body">
                  @if (session()->has('alert'))
                  <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ session()->get('alert') }}</strong>
                  </div>
                  @endif
                  <form method="POST" action="{{ route('ps.update', [], false) }}">
                    @csrf
                    <div class="form-group row">
                      <label for="scat" class="col-md-4 col-form-label text-md-right">Skill Category</label>
                      <div class="col-md-6">
                        <select class="form-control" id="scat" name="scat" >
                          <option value="0" title="All">Any</option>
                          @foreach ($skillcat as $act)
                          <option value="{{ $act->id }}" title="{{ $act->name }}" >{{ $act->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="stype" class="col-md-4 col-form-label text-md-right">Skill Type</label>
                      <div class="col-md-6">
                        <select class="form-control" id="stype" name="stype" >
                          <option value="0" title="All">Any</option>
                          @foreach ($skilltype as $act)
                          <option value="{{ $act->id }}" title="{{ $act->name }}" >{{ $act->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="csid" class="col-md-4 col-form-label text-md-right">The Skill to add</label>
                      <div class="col-md-6">
                        <select class="form-control" id="csid" name="csid" required >
                          @foreach ($skills as $act)
                          <option value="{{ $act->id }}" title="{{ $act->name }}" >{{ $act->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <legend class="col-form-label col-md-4 pt-0 text-sm-right">Competency Level</legend>
                      <div class="col-sm-8">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="rate" id="noob" value="1" onchange="expertlevel()" checked />
                          <label class="form-check-label" for="noob">Beginner</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="rate" id="soso" value="2" onchange="expertlevel()" />
                          <label class="form-check-label" for="soso">Intermediate</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="rate" id="e1337" value="3" onchange="expertlevel()" />
                          <label class="form-check-label" for="e1337">Expert</label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                        <label for="ddremark" class="col-md-4 col-form-label text-md-right">Remarks</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control" name="remark" id="ddremark" placeholder="Additional info" />
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                          <button type="submit" class="btn btn-primary">Add. No edit / delete yet</button>
                        </div>
                    </div>
                    <input type="hidden" name="staff_id" value="{{ $user->id }}"  />
                  </form>
                </div>
            </div>
            @endif
            <div class="card mb-3">
              <div class="card-header">Current Skillset for {{ $user->name }}</div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="perfsameri" class="table table-striped table-hover table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">Category</th>
                        <th scope="col">Type</th>
                        <th scope="col">Skill</th>
                        <th scope="col">Competency</th>
                        <th scope="col">Status</th>
                        <th scope="col">Details</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($pskills as $acts)
                      <tr>
                        <td>{{ $acts->CommonSkill->SkillCategory->name }}</td>
                        <td>{{ $acts->CommonSkill->SkillType->name }}</td>
                        <td>{{ $acts->CommonSkill->name }}</td>
                        <td>{{ $acts->slevel() }}</td>
                        <td>{{ $acts->sStatus() }}</td>
                        <td><a href="{{ route('ps.detail', ['psid' => $acts->id])}}"><button type="button" class="btn btn-sm btn-info" title="Detail"><i class="fa fa-info"></i></button></a></td>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script type="text/javascript">

    function expertlevel(){
      if(document.getElementById("e1337").checked == true){
        document.getElementById("ddremark").required = true;
        $("#ddremark").attr('placeholder', 'What makes you think you are an expert');
      } else {
        document.getElementById("ddremark").required = false;
        $("#ddremark").attr('placeholder', 'Additional info');
      }
    }

    $("#scat").on('change', function(){
        const url='{{ route("ss.api.gettype")}}';

        $.ajax({
        url: url+"?cat="+$("#scat").val(),
        type: "GET",
        success: function(resp) {
            $( "#stype" ).html("");
            $( "#csid" ).html("");
            $( "#stype" ).append('<option hidden disabled selected value="">Select Type</option>');
            $( "#stype" ).append('<option value="0">Any</option>');
            resp.forEach(updateStype);
        },
            error: function(err) {
                // respjson.forEach(myFunction);
                alert("failed");
            }
        });
    });

    function updateStype(item, index){
        $( "#stype" ).append('<option value="'+item.id+'">'+item.name+'</option>');
    }


    $("#stype").on('change', function(){
        const url2='{{ route("ss.api.getskill")}}';
        $.ajax({
        url: url2+"?cat="+$("#scat").val()+"&type="+$("#stype").val(),
        type: "GET",
        success: function(resp) {
            $( "#csid" ).html("");
            $( "#csid" ).append('<option hidden disabled selected value="">Select Skill</option>');
            resp.forEach(updateScid);
        },
            error: function(err) {
                // respjson.forEach(myFunction);
                alert("failed");
            }
        });
    });

    function updateScid(item, index){
        $( "#csid" ).append('<option value="'+item.id+'">'+item.name+'</option>');
    }

    $(document).ready(function() {
      $('#perfsameri').DataTable();
      $('#csid').select2();
    } );
</script>

@endsection
