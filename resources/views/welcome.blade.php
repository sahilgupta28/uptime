<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{env('APP_NAME')}} | Home</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <style>
  .fakeimg {
    height: 200px;
    background: #aaa;
  }
  </style>
</head>
<body>

<div class="jumbotron text-center" style="margin-bottom:0">
  <h1>Uptime Checker</h1>
  <p>Check your uptime!</p> 
</div>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <a class="navbar-brand" href="#">Home</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="#">About us</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">All Sites</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Contact us</a>
      </li>    
    </ul>
  </div>  
</nav>

<div class="container" style="margin-top:30px">
  <div class="row">
    <div class="col-md-12">
        <form method="post" action="{{route('uptime.save')}}">
        @csrf
            <div class="form-row">  
                <div class="form-group col-md-3">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email" name="email">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group col-md-3">
                    <label for="site-name">Site name</label>
                    <input type="text" class="form-control" id="site-name" placeholder="Site Name" name="site_name">
                </div>
                <div class="form-group col-md-3">
                    <label for="site-url">Site url</label>
                    <input type="text" class="form-control" id="site-url" placeholder="Site URL" name="site_url">
                </div>
                <div class="form-group col-md-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
        <hr class="d-sm-none">
    </div>
    <div class="col-md-12">
    <table class="table">
        <thead class='thead-dark'>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Email</th>
                <th scope="col">Site name</th>
                <th scope="col">Site URL</th>
                <th scope="col">Created at</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sites as $key => $site)
            <tr>
                <th scope="row">{{++$key}}</th>
                <td>{{$site['email']}}</td>
                <td>{{$site['site_name']}}</td>
                <td>{{$site['site_url']}}</td>
                <td>{{$site['created_at']}}</td>
                <td><button type="button" class="btn btn-primary" id="edit-site" data-id="{{$site['id']}}" data-document-id="{{$site['document_id']}}">Edit</button> | Delete</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
  </div>
</div>

<div class="modal" id="edit-site-modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title" id="edit-site-modal-header">Edit</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="post" action="#" id="edit-site-form">
            @csrf
            @method('PUT')
            <div class="form">  
                <div class="form-group">
                    <label for="edit-email">Email address</label>
                    <input type="email" class="form-control" id="edit-email" aria-describedby="emailHelp" placeholder="Enter email" name="email">
                    <small id="editemailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group">
                    <label for="edit-site-name">Site name</label>
                    <input type="text" class="form-control" id="edit-site-name" placeholder="Site Name" name="site_name">
                </div>
                <div class="form-group">
                    <label for="edit-site-url">Site url</label>
                    <input type="text" class="form-control" id="edit-site-url" placeholder="Site URL" name="site_url">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
<script>
$('#edit-site').on('click', function(){
    let id= $(this).attr('data-id');
    let document_id = $(this).attr('data-document-id');
    console.log(document_id);
    $.ajax({
        url: "{{url('/uptime')}}" + '/' + id,
        method : 'get',
        cache: false,
        success: function(site_data){
            if(!site_data){
                console.log('Something went wrong.');
            }
            site_data = site_data[0];
            $('#edit-site-name').val(site_data.site_name);
            $('#edit-email').val(site_data.email);
            $('#edit-site-url').val(site_data.site_url);
            $('#edit-site-modal-header').text('Edit '+site_data.site_name)
            $('#edit-site-modal').show();
            $('#edit-site-form').attr('action',"{{url('/uptime')}}" + '/' + document_id);
        }
    });
})
</script>
</html>
