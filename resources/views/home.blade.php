<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts.header')
        <title>{{ config('app.name', 'Home') }}</title>
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
                    <h1 class="m-0">Dashboard</h1>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                    </ol>
                  </div><!-- /.col -->
                </div><!-- /.row -->
              </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
        
            <!-- Main content -->
            <section class="content">
              <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                      <div class="inner">
                        <h3>{{@$totalProducts}}</h3>
        
                        <p>Total Raw Products</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-bag"></i>
                      </div>
                      <a href="/productDetail" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                      <div class="inner">
                        <h3>{{@$totalPrice}}</sup></h3>
        
                        <p>Amount Spent</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                      </div>
                      <a href="/purchase" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                      <div class="inner">
                        <h3>{{@$totalQty}}</h3>
        
                        <p>Total Built Parts</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-person-add"></i>
                      </div>
                      <a href="/stock" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                      <div class="inner">
                        <h3>{{@$totalUser}}</h3>
        
                        <p>Total Active Users</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                      </div>
                      <a href="/user" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                </div>
                <!-- /.row -->
                <!-- Main row -->
                <br>
                <div class="row">
                  <section class="col-lg-12">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title"> Built Parts </h3>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        <div class="row" id="layersData">

                        </div>
                      </div>
                      <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                  </section>
                  <!-- /. col -->
                </div>
                <!-- /.row -->

                <div class="row">
                  <section class="col-lg-12">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title"> Ready To Sell </h3>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-12">
                            <table class="table table-hover table-striped" id="newTable">
                              <thead>
                                <tr>
                                  <th>ID#</th>
                                  <th>Product Name</th>
                                  <th>Layers</th>
                                  <th>Ready To Sell</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                  </section>
                  <!-- /. col -->
                </div>
                <!-- /.row -->

                <div class="row">
                  <!--  col -->
                  <section class="col-lg-12 connectedSortable">
                    <!-- Custom tabs (Charts with tabs)-->
        
                    <!-- TO DO List -->
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">
                          <i class="ion ion-clipboard mr-1"></i>
                          To Do List
                        </h3>
        
                        <div class="card-tools">
                          <ul class="pagination pagination-sm">
                            <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
                            <li class="page-item"><a href="#" class="page-link">1</a></li>
                            <li class="page-item"><a href="#" class="page-link">2</a></li>
                            <li class="page-item"><a href="#" class="page-link">3</a></li>
                            <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
                          </ul>
                        </div>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        <ul class="todo-list" data-widget="todo-list">
                          <li>
                            <!-- drag handle -->
                            <span class="handle">
                              <i class="fas fa-ellipsis-v"></i>
                              <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <!-- checkbox -->
                            <div  class="icheck-primary d-inline ml-2">
                              <input type="checkbox" value="" name="todo1" id="todoCheck1">
                              <label for="todoCheck1"></label>
                            </div>
                            <!-- todo text -->
                            <span class="text">Design a nice theme</span>
                            <!-- Emphasis label -->
                            <small class="badge badge-danger"><i class="far fa-clock"></i> 2 mins</small>
                            <!-- General tools such as edit or delete-->
                            <div class="tools">
                              <i class="fas fa-edit"></i>
                              <i class="fas fa-trash-o"></i>
                            </div>
                          </li>
                          <li>
                            <span class="handle">
                              <i class="fas fa-ellipsis-v"></i>
                              <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div  class="icheck-primary d-inline ml-2">
                              <input type="checkbox" value="" name="todo2" id="todoCheck2" checked>
                              <label for="todoCheck2"></label>
                            </div>
                            <span class="text">Make the theme responsive</span>
                            <small class="badge badge-info"><i class="far fa-clock"></i> 4 hours</small>
                            <div class="tools">
                              <i class="fas fa-edit"></i>
                              <i class="fas fa-trash-o"></i>
                            </div>
                          </li>
                          <li>
                            <span class="handle">
                              <i class="fas fa-ellipsis-v"></i>
                              <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div  class="icheck-primary d-inline ml-2">
                              <input type="checkbox" value="" name="todo3" id="todoCheck3">
                              <label for="todoCheck3"></label>
                            </div>
                            <span class="text">Let theme shine like a star</span>
                            <small class="badge badge-warning"><i class="far fa-clock"></i> 1 day</small>
                            <div class="tools">
                              <i class="fas fa-edit"></i>
                              <i class="fas fa-trash-o"></i>
                            </div>
                          </li>
                          <li>
                            <span class="handle">
                              <i class="fas fa-ellipsis-v"></i>
                              <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div  class="icheck-primary d-inline ml-2">
                              <input type="checkbox" value="" name="todo4" id="todoCheck4">
                              <label for="todoCheck4"></label>
                            </div>
                            <span class="text">Let theme shine like a star</span>
                            <small class="badge badge-success"><i class="far fa-clock"></i> 3 days</small>
                            <div class="tools">
                              <i class="fas fa-edit"></i>
                              <i class="fas fa-trash-o"></i>
                            </div>
                          </li>
                          <li>
                            <span class="handle">
                              <i class="fas fa-ellipsis-v"></i>
                              <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div  class="icheck-primary d-inline ml-2">
                              <input type="checkbox" value="" name="todo5" id="todoCheck5">
                              <label for="todoCheck5"></label>
                            </div>
                            <span class="text">Check your messages and notifications</span>
                            <small class="badge badge-primary"><i class="far fa-clock"></i> 1 week</small>
                            <div class="tools">
                              <i class="fas fa-edit"></i>
                              <i class="fas fa-trash-o"></i>
                            </div>
                          </li>
                          <li>
                            <span class="handle">
                              <i class="fas fa-ellipsis-v"></i>
                              <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div  class="icheck-primary d-inline ml-2">
                              <input type="checkbox" value="" name="todo6" id="todoCheck6">
                              <label for="todoCheck6"></label>
                            </div>
                            <span class="text">Let theme shine like a star</span>
                            <small class="badge badge-secondary"><i class="far fa-clock"></i> 1 month</small>
                            <div class="tools">
                              <i class="fas fa-edit"></i>
                              <i class="fas fa-trash-o"></i>
                            </div>
                          </li>
                        </ul>
                      </div>
                      <!-- /.card-body -->
                      <div class="card-footer clearfix">
                        <button type="button" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Add item</button>
                      </div>
                    </div>
                    <!-- /.card -->
                  </section>
                  <!-- /. col -->
                </div>
                <!-- /.row (main row) -->
              </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
          </div>
          <!-- /.content-wrapper -->
        
          <!-- Control Sidebar -->
          <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
          </aside>
          <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->
        @include('layouts.footer')
        <script>
          $(document).ready(function () { 

            $("#newTable").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    ajax: ({
                        type: "post",
                        url: "/newTable",
                        data: "data",
                        dataType: "json",
                    }),
                    columns: [
                        { data : 'product_id' },
                        { data : 'product_name' },
                        { data : 'layers' },
                        { data : 'r2s' },
                    ]
                })//Datatable

            $.ajax({
              type: "post",
              url: "/loadlayersDet",
              data: "data",
              dataType: "json",
              success: function (response) {
                console.log(response);
                $('#layersData').html(response)

              }
            });
            
          });//documetn Ready
        </script>
    </body>
</html>
