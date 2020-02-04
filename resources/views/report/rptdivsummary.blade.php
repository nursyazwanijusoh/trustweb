@extends('layouts.app')

@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Select division and time range</div>
                <div class="card-body">
                  <form method="GET" action="{{ route('report.gwd.divsum', [], false) }}">
                    <div class="form-group row">
                      <label for="divid" class="col-md-4 col-form-label text-md-right">Division</label>
                      <div class="col-md-6">
                        <select id="divid" class="form-control{{ $errors->has('did') ? ' is-invalid' : '' }}" name="did" required>
                          @foreach ($dlist as $atask)
                          @if(isset($seldiv) && $atask->id == $seldiv->id)
                          <option value="{{ $atask->id }}" selected>{{ $atask->pporgunit }} - {{ $atask->pporgunitdesc }}</option>
                          @else
                          <option value="{{ $atask->id }}" >{{ $atask->pporgunit }} - {{ $atask->pporgunitdesc }}</option>
                          @endif
                          @endforeach
                        </select>
                        @if ($errors->has('gid'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('gid') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                    <div class="form-group row">
                        <label for="fdate" class="col-md-4 col-form-label text-md-right">From</label>
                        <div class="col-md-6">
                          <input type="date" class="form-control{{ $errors->has('fdate') ? ' is-invalid' : '' }}" name="fdate" id="fdate" value="{{ old('fdate', $sdate) }}" required />
                          @if ($errors->has('fdate'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('fdate') }}</strong>
                              </span>
                          @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tdate" class="col-md-4 col-form-label text-md-right">To</label>
                        <div class="col-md-6">
                          <input type="date" class="form-control{{ $errors->has('tdate') ? ' is-invalid' : '' }}" name="tdate" id="tdate" value="{{ old('tdate', $edate) }}" required />
                          @if ($errors->has('tdate'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('tdate') }}</strong>
                              </span>
                          @endif
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col text-center">
                            <button type="submit" name="action" value="graph" class="btn btn-primary">Get Summary</button>
                            <button type="submit" name="action" value="excel" class="btn btn-primary">Download Details</button>
                        </div>
                    </div>
                  </form>
                  <br />
                </div>
              </div>
              <br />
              @if(isset($seldiv))
              <div class="card mb-3">
                <div class="card-header">Performance Summary for {{ $seldiv->pporgunitdesc }}</div>
                <div class="card-body">
                  {!! $sumchart->render() !!}
                </div>
              </div>
              <div class="card mb-3">
                <div class="card-header">Zero Performers</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="taskdetailtable" class="table table-striped table-hover table-bordered">
                      <thead>
                        <tr>
                          <th scope="col">Staff No</th>
                          <th scope="col">Name</th>
                          <th scope="col">Email</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($problemdudes as $acts)
                        <tr>
                          <td><a  href="{{ route('staff', ['staff_id' => $acts->id], false) }}">{{ $acts->staff_no }}</a></td>
                          <td>{{ $acts->name }}</td>
                          <td>{{ $acts->email }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              @endif
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js "></script>

<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#divid').select2();

    $('#taskdetailtable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel'
        ]
    });

});

</script>
@endsection
