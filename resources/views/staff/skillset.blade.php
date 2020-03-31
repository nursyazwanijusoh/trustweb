@extends('layouts.app')

@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
      @if($isvisitor == false || $isboss == true)
      <div class="col-xl-5">
        <div class="card mb-3">
            <div class="card-header bg-info text-white">Add Skill To Your Arsenal</div>
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
                  <div class="col-md-8">
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
                  <div class="col-md-8">
                    <select class="form-control" id="stype" name="stype" >
                      <option value="0" title="All">Any</option>
                      @foreach ($skilltype as $act)
                      <option value="{{ $act->id }}" title="{{ $act->name }}" >{{ $act->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="csid" class="col-md-4 col-form-label text-md-right">Skill</label>
                  <div class="col-md-8">
                    <select class="form-control" id="csid" name="csid" required >
                      @foreach ($skills as $act)
                      <option value="{{ $act->id }}" title="{{ $act->name }}" >{{ $act->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <legend class="col-form-label col-md-4 pt-0 text-md-right">Competency Level</legend>
                  <div class="col-md-8">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="rate" id="noob" value="1" onchange="expertlevel()" checked />
                      <label class="form-check-label" title="I know some basic" for="noob">Beginner</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="rate" id="soso" value="2" onchange="expertlevel()" />
                      <label class="form-check-label" title="I can work independently" for="soso">Intermediate</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="rate" id="e1337" value="3" onchange="expertlevel()" />
                      <label class="form-check-label" title="I can teach others" for="e1337">Expert</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                    <label for="ddremark" class="col-md-4 col-form-label text-md-right">Justification</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="remark" id="ddremark" placeholder="Additional info" />
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col text-center">
                      <button type="submit" class="btn btn-primary" title="Tambah kemahiran">Add</button>
                    </div>
                </div>
                <input type="hidden" name="staff_id" value="{{ $user->id }}"  />
              </form>
            </div>
        </div>
      </div>
      @endif
      <div class="col-xl-7">
        <div class="card mb-3">
          <div class="card-header  bg-info text-white">Current Skillset for <a class="badge badge-secondary" href="{{ route('staff', ['staff_id' => $user->id ])}}">{{ $user->name }}</a></div>
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
                    <td><a href="{{ route('ps.detail', ['psid' => $acts->id])}}"><button type="button" class="btn btn-sm btn-info" title="Detail, Edit, Delete or Approve here"><i class="fa fa-info"></i></button></a></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      @if($isvisitor == false || $isboss == true)
      <div class="col-xl-6">
        <div class="card mb-3">
          <div class="card-header bg-success text-white">Add Past Involvement</div>
          <div class="card-body">
            <form method="POST" action="{{ route('ps.addexp', [], false) }}">
              @csrf
              <div class="form-group row">
                <label for="bauid" class="col-md-4 col-form-label text-md-right">Project / BAU System</label>
                <div class="col-md-8">
                  <select class="form-control" id="bauid" name="bauid" required >
                    @foreach ($bes as $act)
                    <option value="{{ $act->id }}" title="{{ $act->name }}" >{{ $act->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="roleid" class="col-md-4 col-form-label text-md-right">Roles</label>
                <div class="col-md-8">
                  <select class="form-control" id="roleid" name="roleid[]" multiple required >
                    <option disabled>You can select multiple roles</option>
                    @foreach ($jobskop as $act)
                    <option value="{{ $act->id }}" title="{{ $act->hint }}" >{{ $act->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group row mb-0">
                  <div class="col text-center">
                    <button type="submit" class="btn btn-primary" title="Tambah Pengalaman">Add / Edit</button>
                  </div>
              </div>
              <input type="hidden" name="staff_id" value="{{ $user->id }}"  />
            </form>
          </div>
        </div>
      </div>
      @endif
      <div class="col-xl-5">
        <div class="card mb-3">
          <div class="card-header bg-success text-white">Experiences</div>
          <div class="card-body p-1">
            <div class="row no-gutters">
              @foreach($pastexps as $bexp)
              <div class="col-6 col-sm-4 col-md-3 col-xl-6">
              <!-- <div class="col-auto"> -->
                <div class="card bg-light m-1">
                  <div class="card-body p-1">
                    <div class="row">
                      <div class="col">
                        <span class="font-weight-bold">{{ $bexp->BauExp->name }}</span>
                        @foreach($bexp->roles as $ruol)
                        <p class="small my-1 text-secondary">{{ $ruol->name }}</p>
                        @endforeach
                      </div>
                      @if($isvisitor == false || $isboss == true)
                      <div class="col-4 text-right">
                        <form method="post" action="{{ route('ps.delexp')}}">
                          @csrf
                          <input type="hidden" name="uid" value="{{ $user->id }}" />
                          <input type="hidden" name="beid" value="{{ $bexp->id }}"  />
                          <button type="submit" class="btn btn-sm btn-warning" title="remove"><i class="fa fa-times"></i></button>
                        </form>
                      </div>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
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
      $('#perfsameri').DataTable({
        "pageLength": 5
      });
      $('#bexps').DataTable({
        "pageLength": 5
      });
      $('#csid').select2({
          width: '100%'
      });
      $('#bauid').select2({
          width: '100%'
      });
      $('#roleid').select2({
          width: '100%'
      });
    } );
</script>

@endsection
