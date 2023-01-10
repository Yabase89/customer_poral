<html>
    <head>
        <title>Form validation</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    </head>
    <body>
        <div class="container mt-5">
        @if(Session::get('login_type') == 'admin' )
        <h1>List Customers</h1>
            <div class="row">
                <div class="col-md-12">
                    <span>Welcome {{Session::get('login_firstname')}} {{Session::get('login_lastname')}}</span>
                    <a href="{{url('logout')}}" class="btn btn-warning">Log Out</a>
                </div>
                <div class="col-md-12">
                    @if(Session::has('message'))
                    <div class="alert alert-{{Session::get('class')}} alert-dismissible fade show" role="alert">
                        {{Session::get('message')}}
                    </div>
                       
                    @endif
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>DOB</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $cust)
                                <tr>
                                    <td>{{$cust->first_name}}</td>
                                    <td>{{$cust->last_name}}</td>
                                    <td>{{$cust->email}}</td>
                                    <td>{{$cust->dob}}</td>
                                    <td>
                                        @if($cust->status==0)
                                        <span class="not_Approve">Pending</span>
                                        @elseif($cust->status==2)
                                        <span class="Rejected">Rejected</span>
                                        @else
                                        <span class="Approved">Approved</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{url('customer-approve')}}/{{$cust->id}}" class="btn btn-warning">Approve</a>
                                        <a href="{{url('customer-reject')}}/{{$cust->id}}" class="btn btn-danger">Reject</a>
                                    </td>
                                </tr>
                            @endforeach   
                        </tbody>
                    </table>
                    {{$customers}}
                </div>
            </row>
        @else
        <h1>Hi, {{Session::get('login_firstname')}} {{Session::get('login_lastname')}} Welcome to our portal. You are logged in as customer</h1>
        <div class="col-md-12">
                    <a href="{{url('logout')}}" class="btn btn-warning">Log Out</a>
        </div>
        @endif    
        </div>
    </body>
</html>