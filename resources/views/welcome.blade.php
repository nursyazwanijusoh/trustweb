@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">trUSt Web</div>
                <div class="card-body">
                  <h5 class="card-title">Modules</h5>
                  <div class="card-columns">
                    <div class="card bg-light">
                      <div class="card-header text-center">Hot Desk</div>
                      <div class="card-body">
                        <p class="card-text">
                          Agile workspace. Free seating for everyone
                        </p>
                        <h5 class="card-title">Admin</h5>
                        <p class="card-text">
                          <ul>
                            <li>Manage Inventory</li>
                            <li>Manage Staff Access</li>
                          </ul>
                        </p>
                      </div>
                    </div>
                    <div class="card bg-light">
                      <div class="card-header text-center">Group Work Distribution</div>
                      <div class="card-body">
                        <p class="card-text">
                          Make your work be known. Keep track what you have done, and for how long. :D
                        </p>
                        <h5 class="card-title">Admin</h5>
                        <p class="card-text">
                          <ul>
                            <li>Manage Task Type</li>
                            <li>Manage Staff Access</li>
                          </ul>
                        </p>
                        <h5 class="card-title">Superior</h5>
                        <p class="card-text">
                          <ul>
                            <li>Manage Staff's Task</li>
                          </ul>
                        </p>
                        <h5 class="card-title">Staff / General</h5>
                        <p class="card-text">
                          <ul>
                            <li>Manage Task</li>
                            <li>Update daily activity</li>
                          </ul>
                        </p>
                        <p class="card-text">
                          GWD - Eliminating the needs for human interactions since 2018
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
