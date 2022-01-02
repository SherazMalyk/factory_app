<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts.header')
        <title>{{ config('app.name', 'Layers') }}</title>
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
                                <h1 class="m-0">Layers</h1>
                            </div><!-- col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="/layer">Layers</a></li>
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
                                    {{-- <div class="card-header">
                                        <h5 class="m-0">Layers</h5>
                                    </div> --}}<!--card-header-->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <form action="/LayerController" method="post" class="formData">
                                                    @csrf
                                                    <div class="form-group">
                                                        <div class="label">Layer Name</div>
                                                        <input id="layer_name" required placeholder="(2020,2021) (18,20,24) etc" class="form-control form-control-sm" type="text" name="layer_name">
                                                    </div><!-- form-group -->
                                                    <div class="form-group">
                                                        <div class="label">Layer Status</div>
                                                        <select name="layer_status" id="layer_status"  required class="form-control form-control-sm">
                                                            <option value="">--SELECT--</option>
                                                            <option value="1">Active</option>
                                                            <option value="0">Deactive</option>
                                                        </select>
                                                    </div><!-- form-group -->
                                                    <button class="btn btn-info btn-sm" type="submit">Submit</button>
                                                </form>
                                            </div><!--col-4-->
                                            <div class="col-md-8">
                                                <table id="layerTable" class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>ID#</th>
                                                            <th>Layer Name</th>
                                                            <th>Layer Status</th>
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
            var layerTable
            $(document).ready( function() {

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
                            $('.formData').attr('action','LayerController')
                            layerTable.ajax.reload(null, false)
                            Toast.fire({
                                icon: response.sts,
                                title: response.msg
                            })
                        }//success
                    });//ajax Call
                })//formData

                layerTable = $("#layerTable").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    ajax: ({
                        type: "post",
                        url: "/loadModel",
                        data: "data",
                        dataType: "json",
                    }),
                    columns: [
                        { data : 'layer_id' },
                        { data : 'layer_name' },
                        { data : 'status' },
                        { data : 'action' },
                    ]
                })//Datatable
                $(document).on('click', '.editLayer', function(){
                    var layer_id = $(this).attr('id')
                    $.ajax({
                        type: "POST",
                        url: "/editLayer",
                        data: {layer_id: layer_id},
                        dataType: "json",
                        success: function (response) {
                            $('.formData').attr('action','updateLayer/'+response.layer_id)
                            $('#layer_name').val(response.layer_name)
                            $('#layer_status').val(response.layer_status)
                        }
                    });
                })//editLayer

                $(document).on('click', '.deleteLayer', function(){
                    var layer_id = $(this).attr('id')
                    var i = confirm('Do You Really Want To Delete')
                    if (i) {
                        $.ajax({
                            type: "post",
                            url: "/deleteLayer",
                            data: {layer_id: layer_id},
                            dataType: "json",
                            success: function (response) {
                                $('.formData')[0].reset()
                                Toast.fire({
                                    icon: response.sts,
                                    title: response.msg
                                })
                                layerTable.ajax.reload(null, false)
                            }
                        });
                    }
                })

            })//Document Ready Function
        </script>
    </body>
</html>
