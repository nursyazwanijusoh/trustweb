@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">User Guide</div>
                <div class="card-body">
                  <p class="card-text">
Welcome to trUSt Web. This is a quick guide on how to use this web application.
                  </p>
                  <p class="card-text">
Content:<br />
<a href="#staffactivity">Staff Activities</a>
<ul>
  <li><a href="#managetask">Managing Task</a></li>
  <li><a href="#updateactivity">Updating Daily Activities</a></li>
</ul>
<a href="#reports">Reports</a>
<ul>
  <li><a href="#reports">to do</a></li>
</ul>
<a href="#admin">Admin Activities</a>
<ul>
  <li><a href="#ttmanage">Task Type management</a></li>
  <li><a href="#hdinventory">Hot Desking - Inventories</a></li>
  <li><a href="#hdsm">Hot Desking - Staff Management</a></li>
</ul>
                  </p>
                  <h5 class="card-title"></h5>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><a id="staffactivity">Staff Activities</a></div>
                <div class="card-body">
                  <h5 class="card-title"><a id="managetask">Managing Task</a></h5>
                  <p class="card-text">
Daily activities revolves around task. Task can be anything, from your routine BAU supports, BE involvements, and even lepaking mengeteh at riverside
                  </p>
                  <p class="card-text">
To create task, navigate to the <a href="{{ route('staff', [], false)}}">Staff Home</a> page using the top right menu<br />
<img src="{{ asset('img/top_right.png')}}" /><br /><br />
Then, navigate to the <a href="{{ route('staff.t', [], false)}}">Task Management</a> page from the Action menu. In there, you can view all of your tasks, and add new task if required.<br />
<img src="{{ asset('img/staff_home1.png')}}" /><br /><br />
If there is no relevant 'Task Type' available for you, feel free to contact the <a href="{{ route('adminlist', [], false)}}">admins</a> so that they can create the new task type for you.<br />
                  </p>
                  <p class="card-text">
Note that Task Type are meant to be a 'high-level' type of task. For example, there might be a task type 'Business Enhancement'. Should you involve in any BE activities, such as 'C41234 - New Product Model', you can create a task named 'C41234 - New Product Model', with type 'Business Enhancement', regardless of your role in that activity (consultant? developer? QA?).<br />
The detail of what you do for that task will come in the next part: Daily activities
                  </p>
                  <p class="card-text">
You can click the task to see what are the activities that you have done under it. If the task is considered completed, you can close the task accordingly so that it will not appear again in the list of task under your daily activity form
                  </p>
                  <p class="card-text">
Note: you can also add tasks for your subordinates from their task management page
                  </p>
                  <h5 class="card-title"><a id="updateactivity">Updating Daily Activities</a></h5>
                  <p class="card-text">
Once you have at least an open task, you can update your daily activity from the <a href="{{ route('staff.addact', [], false)}}">Update Daily Activity</a> Action menu. <br />
The list of tasks that you add previously will appear here. Select which task that you want to add the activity to from the dropdown menu.<br />
These activities will be tied back to their respective tasks. You can review them back from the task details page.
                  </p>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><a id="reports">Reports</a></div>
                <div class="card-body">
                  <p class="card-text">Pending development</p>
                  <h5 class="card-title"></h5>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><a id="admin">Admin Activities</a></div>
                <div class="card-body">
                  <h5 class="card-title"><a id="ttmanage">Task Type management</a></h5>
                  <p class="card-text">
You can manage list of task type by going to the <a href="{{ route('admin', [], false)}}">Admin</a> page from the top right menu, then selecting <a href="{{ route('admin.tt', [], false)}}">Task Type Management</a>.
                  </p>
                  <p class="card-text">
As mentioned above, if possible, try not to create task type that is too specific (meeting, development, testing, etc). That should be part of the activity within the task, not as the task itself.
                  </p>
                  <h5 class="card-title"><a id="hdinventory">Hot Desking - Inventories</a></h5>
                  <p class="card-text">Pending content</p>
                  <h5 class="card-title"><a id="hdsm">Hot Desking - Staff Management</a></h5>
                  <p class="card-text">Pending content</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
