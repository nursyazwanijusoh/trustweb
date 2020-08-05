@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">Opportunity Project List</div>

              </div>
              <div class="card">
                <div class="card-header">Project List</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="projmeja" class="table-striped table-hover display" style="width:100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th >Proj ID</th>
                          <th >Descr</th>
                          <th >Start Date</th>
                          <th >End Date</th>
                          <th >Status</th>
                          <th class="none"></th>
                
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($pojeks as $proj)
                      
                      <tr>
                      <td></td>
                      <td>{{$proj->id}}</td>
                      <td>{{$proj->descr}}</td>
                      <td>{{$proj->start_date}}</td>
                      <td>{{$proj->end_date}}</td>
                      <td>{{$proj->status}}</td>
                      <td style='padding:0'>
                      <table style="margin:0; display:inline"
                      class="table-bordered"> 
                      @foreach($proj->assigments as $assg)
                       <tr>
                       <td>dd</td>
                       <td>dooooooooooooooooooooooood</td>
                       <td>Apply</td>
                       </tr>
                      @endforeach
                      </table>
                      </td>

               
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

<link href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css" rel="stylesheet" media="screen">
<link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet" media="screen">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js
"></script>
<script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>


<script type="text/javascript">
$(document).ready(function() {
    $('#projmeja').DataTable({
        
        
      responsive: {
            details: {
                type: 'inline',
                target: 'tr'
            }
        },

        columnDefs: [ {
            className: 'control',
            orderable: false,
            targets:   0
        } ],
        order: [ 1, 'asc' ]



    });
} );

</script>


@stop

