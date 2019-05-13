@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">View Hot Desk Checkins by Division</div>
                <div class="card-body">
                  <form method="GET" action="{{ route('hdreports.dbdf', [], false) }}" id="whform">
                    <!-- @csrf -->
                    <!-- <h5 class="card-title">Date range</h5> -->
                    <div class="form-group row">
                        <label for="rptdate" class="col-md-4 col-form-label text-md-right">Date</label>
                        <div class="col-md-6">
                          <input type="date" name="rptdate" id="rptdate" value="{{ $sysdate }}"/>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="lob" class="col-md-4 col-form-label text-md-right">Division</label>
                      <div class="col-md-6">
                        <select class="form-control" id="lob" name="lob">
                          @foreach ($divlist as $atask)
                          <option value="{{ $atask['pporgunit'] }}" >{{ $atask['divname'] . ' (' . $atask['regcount'] . ')' }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Get Division Report</button>
                        </div>
                    </div>
                  </form>
                </div>
                @if(!empty($data))

                <?php
                  $prefsub = '';
                ?>
                <div class="card-header"> </div>
                <div class="card-body">
                  <!-- <h5 class="card-title">List of task type</h5> -->
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Staff ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Seat Label</th>
                        <th scope="col">Check-In time</th>
                        <th scope="col">Check-Out Time</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $atask)

                      <?php
                        if($prefsub != $atask->subunit){
                          $prefsub = $atask->subunit;
                          ?>
                          <tr><td colspan="5" style="background-color:cyan;">{{ $prefsub }}</td></tr>
                          <?php
                        }
                       ?>

                      @if($atask->checkins == 'empty')
                      <tr>
                        <td>{{ $atask->staff_no }}</td>
                        <td>{{ $atask->name }}</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      @else
                      @foreach($atask->checkins as $acek)
                      <tr>
                        <td>{{ $atask->staff_no }}</td>
                        <td>{{ $atask->name }}</td>
                        <td>{{ $acek->loc_detail->label }}</td>
                        <td>{{ $acek->checkin_time }}</td>
                        <td>{{ $acek->checkout_time }}</td>
                      </tr>
                      @endforeach
                      @endif

                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th scope="col">Staff ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Seat Label</th>
                        <th scope="col">Check-In time</th>
                        <th scope="col">Check-Out Time</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
