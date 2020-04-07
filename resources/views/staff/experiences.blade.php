@extends('layouts.app')

@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
      @if($isvisitor == false || $isboss == true)
      <div class="col-lg-6">
        <div class="card mb-3">
          <div class="card-header bg-success text-white">Add Past Involvement</div>
          <div class="card-body">
            @if (session()->has('alert'))
            <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>{{ session()->get('alert') }}</strong>
            </div>
            @endif
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
      <div class="col-xl-12">
        <div class="card mb-3">
          <div class="card-header bg-success text-white">Experiences</div>
          <div class="card-body p-1">
            <div class="row no-gutters">
              @foreach($pastexps as $bexp)
              <div class="col-6 col-sm-4 col-md-3 col-xl-2">
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

    $(document).ready(function() {
      $('#bexps').DataTable();
      $('#bauid').select2({
          width: '100%'
      });
      $('#roleid').select2({
          width: '100%'
      });
    } );
</script>

@endsection
