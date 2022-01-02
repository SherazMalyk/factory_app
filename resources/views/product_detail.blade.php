<!DOCTYPE html>
<html lang="en">
  <head>
    @include('layouts.header')
    <title>{{ config('app.name', 'Products') }}</title>
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
                        <span class="h3 m-0"> Product Details </span> <a href="/product"><span class="fa fa-plus"></span>  Add New</a>
                    </div><!-- col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/productDetail">Product Details</a></li>
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
                      <h5 class="m-0">Product Details</h5>
                  </div> --}}<!--card-header-->
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12">
                        <table class="table table-hover table-striped" id="PDetailTable">
                          <thead>
                            <tr>
                              <th>ID#</th>
                              <th>Product Name</th>
                              <th>Product Type</th>
                              <th>Product Status</th>
                              <th>Layer Names</th>
                              <th>Option Names</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                        </table>
                      </div>{{-- col --}}
                    </div>{{-- row --}}
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
        var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
          var PDetailTable
          $(document).ready( function() {

            PDetailTable = $('#PDetailTable').DataTable({
              "responsive": true,
              "lengthChange": false,
              "autoWidth": false,
              ajax: ({
                type: "post",
                url: "/loadPDetails",
                data: "data",
                dataType: "json",
              }),
              columns: [
                { data : 'product_id' },
                { data : 'product_name' },
                { data : 'product_type' },
                { data : 'product_status' },
                { data : 'layer_name' },
                { data : 'part_name' },
                { data : 'action' },
              ]
            })//Datatable

          })//Document Ready Function
          function deletePDetails(product_id){
            var product_id = product_id
            var i = confirm('Do You Really Want To Delete')
            if (i) {
              $.ajax({
                type: "post",
                url: "deleteProduct",
                data: {product_id: product_id},
                dataType: "json",
                success: function (response) {
                  Toast.fire({
                      icon: response.sts,
                      title: response.msg
                  })
                  PDetailTable.ajax.reload(null, false)                
                }
              });
            }
          }//deletePDetails Function
      </script>
  </body>
</html>
