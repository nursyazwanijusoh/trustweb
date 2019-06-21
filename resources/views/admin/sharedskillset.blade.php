@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Shared Skillset Management</div>
                <div class="card-body">
                  <form method="POST" action="{{ route('skillset.shared.add', [], false) }}">
                    @csrf
                    <h5 class="card-title">Add new skillset</h5>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                        <div class="col-md-6">
                            <input id="name" class="form-control" type="text" name="name" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="skillgroup" class="col-md-4 col-form-label text-md-right">Skill Group</label>
                        <div class="col-md-6">
                            <input id="skillgroup" class="form-control" type="text" name="skillgroup" required>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="skilltype" class="col-md-4 col-form-label text-md-right">Skill Type</label>
                      <div class="col-md-6">
                        <select class="form-control" id="skilltype" name="skilltype">
                          <option value="Technical" >Technical</option>
                          <option value="Soft Skill" >Soft Skill</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Add Skillset</button>
                        </div>
                    </div>
                  </form>
                </div>
                <div class="card-header"> </div>
                <div class="card-body">
                  <h5 class="card-title">List of Shared Skillset</h5>
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Group</th>
                        <th scope="col">Type</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($currtasklist as $atask)
                      <tr>
                        <td>{{ $atask['name'] }}</td>
                        <td>{{ $atask['skillgroup'] }}</td>
                        <td>{{ $atask['skilltype'] }}</td>
                        <td><a href="{{ route('skillset.shared.del', ['taskid' => $atask['id']], false) }}">Remove</a></td>
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
