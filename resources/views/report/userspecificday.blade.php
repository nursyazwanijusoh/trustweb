@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Activities for {{ $name }} on {{ $date }}</div>
                <div class="card-body">
                  <table class="table table-striped table-hover table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">Type</th>
                        <th scope="col">ID / Name</th>
                        <th scope="col">Details</th>
                        <th scope="col">Hours</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $acts)
                      <tr>
                        <td>{{ $acts->ActType->descr }}</td>
                        <td>{{ $acts->parent_number }}</td>
                        <td>{{ $acts->details }}</td>
                        <td>{{ $acts->hours_spent }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
