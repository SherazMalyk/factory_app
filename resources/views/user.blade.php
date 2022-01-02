<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts.header')
        <title>{{ config('app.name', 'User') }}</title>
    </head>
    <body class="sidebar-mini sidebar-collapsed layout-fixed sidebar-closed sidebar-collapse">
        <div class="wrapper">
            
            <!-- Navbar -->
            @include('layouts.topbar')

            <!-- Main Sidebar Container -->
            @include('layouts.sidebar')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Users</h1>
                            </div><!-- col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="/user">Users</a></li>
                                </ol>
                            </div><!-- col -->
                        </div><!-- row -->
                    </div><!-- container-fluid -->
                </div><!-- content-header -->

                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-primary card-outline">
                                    <div class="card-body">
                                        <form action="/addUser" method="post" class="formData">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    @csrf
                                                    <div class="form-group">
                                                        <div class="label">User Name</div>
                                                        <input id="name" required placeholder="Ali etc." class="form-control form-control-sm" type="text" name="name">
                                                    </div><!-- form-group -->
                                                    <div class="form-group">
                                                        <div class="label">User Phone</div>
                                                        <input id="user_phone" class="form-control form-control-sm" placeholder="0300-0000000" type="text" name="user_phone">
                                                    </div><!-- form-group -->
                                                    <div class="form-group">
                                                        <div class="label">User Status</div>
                                                        <select name="user_status" id="user_status"  required class="form-control form-control-sm">
                                                            <option value="">--SELECT--</option>
                                                            <option value="1">Active</option>
                                                            <option value="0">Deactive</option>
                                                        </select>
                                                    </div><!-- form-group -->
                                                    <div class="form-group">
                                                        <div class="label">User Address</div>
                                                        <textarea name="user_address" id="user_address" rows="4" class="form-control form-control-sm"></textarea>
                                                    </div><!-- form-group -->
                                                </div><!--col-6-->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="label">User Email</div>
                                                        <input id="email" required placeholder="abc@xyz.com" class="form-control form-control-sm" type="email" name="email">
                                                    </div><!-- form-group -->
                                                    <div class="form-group" id="userPassword">
                                                        <div class="label">User Password</div>
                                                        <input id="password" required placeholder="* * * * * *" class="form-control form-control-sm" type="text" name="password">
                                                    </div><!-- form-group -->
                                                    <div class="form-group">
                                                        <div class="label">User Type</div>
                                                        <select name="user_type" id="user_type"  required class="form-control form-control-sm">
                                                            <option value="">--SELECT--</option>
                                                            <option value="1">Admin</option>
                                                            <option value="2">Cashier</option>
                                                            <option value="3">Supplier</option>
                                                            <option value="4">Employee</option>
                                                            <option value="5">Customer</option>
                                                        </select>
                                                    </div><!-- form-group -->
                                                    <div class="form-group">
                                                        <div class="label">User Pic</div>
                                                        <input type="file" name="user_pic" id="user_pic" onchange="readURL(this)" style="display: none;">
                                                        <img src="{{asset('storage/user/place.png')}}" id="cstmUserimg" class="cstmUserimg">
                                                    </div><!-- form group -->
                                                </div><!--col-6-->
                                            </div><!--row-->
                                            <button class="btn btn-info btn-sm" type="submit">Submit</button>
                                        </form>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table id="userTable" class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>ID#</th>
                                                            <th>User Pic</th>
                                                            <th>User Name</th>
                                                            <th>User Email</th>
                                                            <th>User Phone</th>
                                                            <th>User Address</th>
                                                            <th>User Role</th>
                                                            <th>User Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div><!--col-8-->
                                        </div><!--row-->
                                    </div><!--card-body-->
                                </div><!--card-->
                            </div><!--col-md-12 -->
                        </div><!-- row -->
                    </div><!-- container-fluid -->
                </div><!-- content -->
            </div><!-- content-wrapper -->
        </div><!-- wrapper -->

        <!-- REQUIRED SCRIPTS -->
        @include('layouts.footer')
        <script>
            var userTable
            $(document).ready( function() {

                $('#cstmUserimg').click(function () {
                    $('#user_pic').click();
                });

                var Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });

                $('.formData').on('submit', function(e){
                    e.preventDefault()
                    $.ajax({
                        type: "POST",
                        url: $(this).attr('action'),
                        processData: false,
                        contentType: false,
                        data: new FormData(this),
                        dataType: "json",
                        success: function (response) {
                            $('.formData')[0].reset()
                            $('.formData').attr('action','/addUser')
                            $('#cstmUserimg').attr('src', 'storage/user/place.png')
                            $('#userPassword').html('<div class="label">User Password</div><input id="password" required placeholder="* * * * * *" class="form-control form-control-sm" type="text" name="password">')
                            userTable.ajax.reload(null, false)
                            Toast.fire({
                                icon: response.sts,
                                title: response.msg
                            })
                        }//success
                    });//ajax Call
                })//formData

                userTable = $("#userTable").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    ajax: ({
                        type: "post",
                        url: "/loadUser",
                        data: "data",
                        dataType: "json",
                    }),
                    columns: [
                        { data : 'id' },
                        { data : 'user_pic' },
                        { data : 'name' },
                        { data : 'email' },
                        { data : 'user_phone' },
                        { data : 'user_address' },
                        { data : 'user_type' },
                        { data : 'user_status' },
                        { data : 'action' },
                    ]
                })//Datatable

                $(document).on('click', '.editUser', function(){
                    var id = $(this).attr('id')
                    $.ajax({
                        type: "POST",
                        url: "/editUser",
                        data: {id: id},
                        dataType: "json",
                        success: function (response) {
                            $('.formData').attr('action','updateUser/'+response.id)
                            $('#name').val(response.name)
                            $('#email').val(response.email)
                            $('#user_phone').val(response.user_phone)
                            $('#user_status').val(response.user_status)
                            $('#user_type').val(response.user_type)
                            $('#user_address').val(response.user_address)
                            $('#userPassword').html('')
                            $('#cstmUserimg').attr('src', response.user_pic)
                        }
                    });
                })//editLayer

                $(document).on('click', '.deleteUser', function(){
                    var id = $(this).attr('id')
                    var i = confirm('Do You Really Want To Delete')
                    if (i) {
                        $.ajax({
                            type: "post",
                            url: "/deleteUser",
                            data: {id: id},
                            dataType: "json",
                            success: function (response) {
                                $('.formData')[0].reset()
                                $('#cstmUserimg').attr('src', 'storage/user/place.png')
                                Toast.fire({
                                    icon: response.sts,
                                    title: response.msg
                                })
                                userTable.ajax.reload(null, false)
                            }
                        });
                    }
                })
            })//Document Ready Function

            //File Input With Preview and Validation
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var fileExtension = ['jpeg', 'jpg', 'png'];
                    var fileSize = (input.files[0].size);
                    var reader = new FileReader;
                    reader.onload = function () { //file is loaded
                        //check image Extentions
                        if ($.inArray($(input).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                        Swal.fire({
                            type: 'error',
                            title: 'Only formats are allowed :' + fileExtension.join(', '),
                            showConfirmButton: true,
                            timer: 4000
                        });
                        }else{
                            //check image size
                            if (fileSize > 4000000) {
                            Swal.fire({
                                type: 'error',
                                title: 'You cannot upload More Than 4MB of Image',
                                showConfirmButton: true,
                                timer: 4000
                            });
                            }else{
                                var img = new Image;
                                img.src = reader.result;
                                img.onload = function() {
                                    if (this.width != this.height) {  
                                        $('#cstmUserimg').attr('src', reader.result);   
                                    }else{
                                    Swal.fire({
                                    type: 'error',
                                    title: 'The Image Must be 1:1 Size',
                                    showConfirmButton: true,
                                    timer: 4000
                                    });
                                    }
                                }    
                            }
                        }
                    };
                reader.readAsDataURL(input.files[0]); 
                }
            }//image upload function
        </script>
    </body>
</html>
